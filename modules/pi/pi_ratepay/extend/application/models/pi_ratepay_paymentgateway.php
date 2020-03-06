<?php

class pi_ratepay_paymentgateway extends pi_ratepay_paymentgateway_parent
{
    /**
     * Payment type
     *
     * @var string
     */
    protected $_paymentId = null;

    /**
     * Payment type config
     *
     * @var array
     */
    protected $paymentMethodIds = array(
        'pi_ratepay_rechnung' => array(
            'connection_timeout' => '-418',
            'denied' => '-400',
            'soft' => '-001',
        ),
        'pi_ratepay_rate' => array(
            'connection_timeout' => '-418',
            'denied' => '-407',
            'soft' => '-001',
        ),
        'pi_ratepay_elv' => array(
            'connection_timeout' => '-418',
            'denied' => '-300',
            'soft' => '-001',
        )
    );

    /**
     * @param string $sPaymentType
     * @return mixed
     */
    protected function _isSandbox($sPaymentType)
    {
        $method = pi_ratepay_util_utilities::getPaymentMethod($sPaymentType);

        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType(strtolower($method), oxRegistry::getSession()->getVariable('shopId'));
        return ($settings->pi_ratepay_settings__sandbox->rawValue);
    }

    /**
     * Check if a RatePay payment type was selected
     *
     * @param object $oOrder  User ordering object
     *
     * @return bool
     */
    protected function isRatePayPayment($oOrder)
    {
        if (in_array($oOrder->oxorder__oxpaymenttype->value, pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD)) {
            return true;
        }
        return false;
    }

    /**
     * Executes payment, returns true on success.
     *
     * @param double $dAmount Goods amount
     * @param object $oOrder  User ordering object
     *
     * @return bool
     */
    public function executePayment($dAmount, &$oOrder)
    {
        if($this->isRatePayPayment($oOrder) === false) {
            return parent::executePayment($dAmount, $oOrder);
        }

        $this->handleRatePayPayment($oOrder, $dAmount);

        return true;
    }

    /**
     * @param  object $oOrder  User ordering object
     * @param  double $dAmount Goods amount
     * @return void
     */
    protected function handleRatePayPayment($oOrder, $dAmount)
    {
        $this->_paymentId = $oOrder->oxorder__oxpaymenttype->value;
        $isSandbox = $this->_isSandbox($this->_paymentId);

        $modelFactory = new ModelFactory();
        $modelFactory->setPaymentType($this->_paymentId);
        $modelFactory->setSandbox($isSandbox);
        $modelFactory->setCountryId($this->getUser()->oxuser__oxcountryid->value);
        $modelFactory->setShopId(oxRegistry::getSession()->getVariable('shopId'));

        $payInit = $modelFactory->doOperation('PAYMENT_INIT');
        if (!$payInit->isSuccessful()) {
            if ($payInit->getReasonCode() != 703 && !$isSandbox) {
                $this->getSession()->setVariable('pi_ratepay_denied', 'denied');
            }
            $this->getSession()->setVariable($this->_paymentId . '_error_id', $this->paymentMethodIds[$this->_paymentId]['denied']);
            oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment', false);
        }

        $transactionId = (string)$payInit->getTransactionId();
        $this->getSession()->setVariable($this->_paymentId . '_trans_id', $transactionId);

        $modelFactory->setTransactionId($transactionId);
        $modelFactory->setCustomerId($this->getUser()->oxuser__oxcustnr->value);
        $modelFactory->setDeviceToken($this->getSession()->getVariable('pi_ratepay_dfp_token'));
        $modelFactory->setBasket($this->getSession()->getBasket());

        $payRequest = $modelFactory->doOperation('PAYMENT_REQUEST');
        if (!$payRequest->isSuccessful()) {
            if ((!$payRequest->getResultCode() == 150) && !$isSandbox) {
                $this->getSession()->setVariable('pi_ratepay_denied', 'denied');
            }

            $message = $payRequest->getCustomerMessage();
            $this->getSession()->setVariable($this->_paymentId . '_message', (string)$message);
            if ($payRequest->getResultCode() == 150 && !empty($message)) {
                $this->getSession()->setVariable($this->_paymentId . '_error_id', $this->paymentMethodIds[$this->_paymentId]['soft']);
            } else {
                $this->getSession()->setVariable($this->_paymentId . '_error_id', $this->paymentMethodIds[$this->_paymentId]['denied']);

            }

            // OX-19: delete order if payment failed
            $oOrder->delete();

            oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment', false);
        }
        $this->getSession()->setVariable($this->_paymentId . '_descriptor', $payRequest->getDescriptor());

        // FINALIZE

        if ($oOrder->getId() != null && $oOrder->getId() != $this->getSession()->getVariable('pi_ratepay_shops_order_id')) {
            $this->getSession()->setVariable('pi_ratepay_shops_order_id', $oOrder->getId());
        }
        $this->_saveRatepayOrder($this->getSession()->getVariable('pi_ratepay_shops_order_id'));
        $tid = $this->getSession()->getVariable($this->_paymentId . '_trans_id');

        $orderLogs = pi_ratepay_LogsService::getInstance()->getLogsList("transaction_id = " . oxDb::getDb(true)->quote($tid));
        foreach ($orderLogs as $log) {
            if (!is_null($oOrder->oxorder__oxordernr)) {
                $log->assign(array('order_number' => $oOrder->oxorder__oxordernr));
            } else {
                $log->assign(array('order_number' => $this->getSession()->getVariable('pi_ratepay_shops_order_id')));
            }
            $log->save();
        }

        $modelFactory->setOrderId($this->getSession()->getVariable('pi_ratepay_shops_order_id'));
        $modelFactory->setTransactionId($tid);
        $modelFactory->doOperation('PAYMENT_CONFIRM');

        $this->getSession()->deleteVariable('pi_ratepay_dfp_token');
    }

    /**
     * Saves order information to ratepay order tables in the db. Used for backend operations.
     *
     * @uses functions _saveRatepayBasketItems
     * @param string $id
     */
    private function _saveRatepayOrder($id)
    {
        $transid = $this->getSession()->getVariable($this->_paymentId . '_trans_id');
        $descriptor = $this->getSession()->getVariable($this->_paymentId . '_descriptor');
        $userbirthdate = $this->getUser()->oxuser__oxbirthdate->value;
        $api = 'api_1.8';

        $ratepayOrder = oxNew('pi_ratepay_orders');
        $ratepayOrder->loadByOrderNumber($id);

        $ratepayOrder->assign(array(
            'order_number' => $id,
            'transaction_id' => $transid,
            'descriptor' => $descriptor,
            'userbirthdate' => $userbirthdate,
            'rp_api' => $api
        ));

        $ratepayOrder->save();

        if ($this->_paymentId === 'pi_ratepay_rate') {
            $totalAmount = $this->getSession()->getVariable('pi_ratepay_rate_total_amount');
            $amount = $this->getSession()->getVariable('pi_ratepay_rate_amount');
            $interestAmount = $this->getSession()->getVariable('pi_ratepay_rate_interest_amount');
            $service_charge = $this->getSession()->getVariable('pi_ratepay_rate_service_charge');
            $annualPercentageRate = $this->getSession()->getVariable('pi_ratepay_rate_annual_percentage_rate');
            $monthlyDebitInterest = $this->getSession()->getVariable('pi_ratepay_rate_monthly_debit_interest');
            $numberOfRates = $this->getSession()->getVariable('pi_ratepay_rate_number_of_rates');
            $rate = $this->getSession()->getVariable('pi_ratepay_rate_rate');
            $lastRate = $this->getSession()->getVariable('pi_ratepay_rate_last_rate');

            $ratepayRateDetails = oxNew('pi_ratepay_ratedetails');
            $ratepayRateDetails->loadByOrderId($id);

            $ratepayRateDetails->assign(array(
                'orderid' => $id,
                'totalamount' => $totalAmount,
                'amount' => $amount,
                'interestamount' => $interestAmount,
                'servicecharge' => $service_charge,
                'annualpercentagerate' => $annualPercentageRate,
                'monthlydebitinterest' => $monthlyDebitInterest,
                'numberofrates' => $numberOfRates,
                'rate' => $rate,
                'lastrate' => $lastRate
            ));

            $ratepayRateDetails->save();
        }

        $this->_saveRatepayBasketItems($id);
    }

    /**
     * Save basket items information to ratepay order details tables in the db.
     *
     * @param string $id
     * @param string $paymentType
     */
    private function _saveRatepayBasketItems($id)
    {
        oxDb::getDb()->execute("DELETE FROM `pi_ratepay_order_details` where order_number = ?", array($id));

        $oBasket = $this->getSession()->getBasket();
        foreach ($oBasket->getContents() as $article) {
            $articlenumber = $article->getArticle()->getId();
            $quantity = $article->getAmount();
            $this->_saveToRatepayOrderDetails($id, $articlenumber, $quantity);
        }

        $specialItems = array('oxwrapping', 'oxgiftcard', 'oxdelivery', 'oxpayment', 'oxtsprotection');
        foreach ($specialItems as $articleNumber) {
            $this->_checkBasketCosts($id, $articleNumber);
        }

        if ($oBasket->getVouchers()) {
            foreach ($oBasket->getVouchers() as $voucher) {
                $articlenumber = $voucher->sVoucherId;
                $quantity = 1;
                $this->_saveToRatepayOrderDetails($id, $articlenumber, $quantity);
            }
        }

        if ($oBasket->getDiscounts()) {
            foreach ($oBasket->getDiscounts() as $discount) {
                $this->_saveToRatepayOrderDetails($id, $discount->sOXID, 1, $discount->dDiscount * -1);
            }
        }
    }

    /**
     * Log Basket costs to RatePAY order details.
     * @param string $id
     * @param string $articleNumber
     */
    private function _checkBasketCosts($id, $articleNumber)
    {
        $articlePrice = $this->getSession()->getBasket()->getCosts($articleNumber);
        if ($articlePrice instanceof oxPrice && $articlePrice->getBruttoPrice() > 0) {
            $this->_saveToRatepayOrderDetails($id, $articleNumber, 1, $articlePrice->getNettoPrice(), round($articlePrice->getVat()));
        }
    }

    /**
     * Save to order details.
     * @param string $id
     * @param string $articleNumber
     * @param int $quantity
     */
    private function _saveToRatepayOrderDetails($id, $articleNumber, $quantity, $price = 0, $vat = 0)
    {
        $ratepayOrderDetails = oxNew('pi_ratepay_orderdetails');

        $ratepayOrderDetails->assign(array(
            'order_number' => $id,
            'article_number' => $articleNumber,
            'unique_article_number' => $articleNumber,
            'price' => $price,
            'vat' => $vat,
            'ordered' => $quantity
        ));

        $ratepayOrderDetails->save();
    }
}
