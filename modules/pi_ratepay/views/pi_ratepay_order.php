<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  PayIntelligent
 * @package   PayIntelligent_RatePAY
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */

/**
 * {@inheritdoc}
 *
 * Additionally sends RatePAY PAYMENT_REQUEST and sets RatePAY payment specific informations in db and session.
 *
 * @package PayIntelligent_RatePAY
 * @extends order
 */
class pi_ratepay_order extends pi_ratepay_order_parent
{

    private $_paymentId = null;

    /**
     * Holds paymentid (basket payment)
     * @var string
     */
    private $_ratepayPaymentType = '';

    /**
     * Executes Order
     *
     * Tests if the payment method is Rate or Rechnung.
     *
     * If order_id is already in use the old transaction with this order_id will be full-cancelled and a new transaction will be created.
     *
     * If true - Checks for 'ord_custinfo' and session challenge (CSRF), if either of them fails returns to order view.
     * It loads basket contents (plus applied price/amount if availabe - checks for stock, checks user data (if no data
     * is set - returns to user login page). Stores order info to database (oxorder::finalizeOrder()) and updates
     * order_id to ratepay logging. Assigns user to special user group according to sum for items
     * (oxuser::onOrderExcebute(); if this option is not disabled in admin).
     * Also it tries a RatePAY PAYMENT_REQUEST. If the payment request fails sets error ids in session and returns to
     * payment view. Finally the user will be redirected to the next page (order::_getNextStep()).
     *
     * If false - execute calls parent::execute which: {@inheritdoc}
     *
     * @uses function _ratepayRequest
     * @uses function _saveRatepayOrder
     * @see  order::execute()
     * @return string
     */
    public function execute()
    {
        $this->_paymentId = $this->getBasket()->getPaymentId();

        if ($this->getSession()->getVariable('pi_ratepay_shops_order_id') != null &&
            $this->getSession()->getBasket()->getOrderId() == $this->getSession()->getVariable('pi_ratepay_shops_order_id')
        ) {

            $trans_id = oxDb::getDb()->getOne("SELECT TRANSACTION_ID FROM pi_ratepay_orders WHERE ORDER_NUMBER = '" . $this->getSession()->getBasket()->getOrderId() . "'");
            $strans_id = oxDb::getDb()->getOne("SELECT TRANSACTION_SHORT_ID FROM pi_ratepay_orders WHERE ORDER_NUMBER = '" . $this->getSession()->getBasket()->getOrderId() . "'");
            $oid = oxDb::getDb()->getOne("SELECT OXORDERNR FROM oxorder WHERE OXID = '" . $this->getSession()->getBasket()->getOrderId() . "'");
            $ratepayRequest = $this->_getRatepayRequest($this->_paymentId);
            $subtype = 'full-cancellation';
            $name = $this->getUser()->oxuser__oxfname->value;
            $surname = $this->getUser()->oxuser__oxlname->value;
            $changePayment = $ratepayRequest->changePayment($trans_id, $strans_id, $subtype, $oid);
            $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId);

            pi_ratepay_LogsService::getInstance()->logRatepayTransaction($this->getSession()->getBasket()->getOrderId(), $trans_id, $paymentMethod, 'PAYMENT_CHANGE', $subtype, $changePayment['request'], $name, $surname, $changePayment['response']);

        }

        if (in_array($this->_paymentId, pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD)) {
            $paymentMethodIds = array(
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

            if (!$this->getSession()->checkSessionChallenge()) {
                return;
            }

            // for compatibility reasons for a while. will be removed in future
            if (oxRegistry::getConfig()->getRequestParameter('ord_custinfo') !== null && !oxRegistry::getConfig()->getRequestParameter('ord_custinfo') && $this->isConfirmCustInfoActive()) {
                $this->_blConfirmCustInfoError = 1;
                return;
            }

            // additional check if we really really have a user now
            if (!$oUser = $this->getUser()) {
                return 'user';
            }

            // get basket contents
            $oBasket = $this->getSession()->getBasket();
            if ($oBasket->getProductsCount()) {

                if (!$this->_ratepayInitRequest()) {
                    if (!$this->getSession()->getVariable($this->_paymentId . '_error_id') == $paymentMethodIds[$this->_paymentId]['connection_timeout'] &&
                        !$this->_isSandbox(pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId))
                    ) {
                        $this->getSession()->setVariable('pi_ratepay_denied', 'denied');
                    }
                    $this->getSession()->setVariable($this->_paymentId . '_error_id', $paymentMethodIds[$this->_paymentId]['denied']);
                    oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment', false);
                }

                if (!$this->_ratepayRequest()) {
                    if ((!$this->getSession()->getVariable($this->_paymentId . '_error_id') == $paymentMethodIds[$this->_paymentId]['connection_timeout']) &&
                        (!$this->getSession()->getVariable($this->_paymentId . '_error_id') == $paymentMethodIds[$this->_paymentId]['soft']) &&
                        !$this->_isSandbox(pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId))
                    ) {
                        $this->getSession()->setVariable('pi_ratepay_denied', 'denied');
                    }

                    $message = $this->getSession()->getVariable($this->_paymentId . '_message');
                    if ($this->getSession()->getVariable($this->_paymentId . '_error_id') == $paymentMethodIds[$this->_paymentId]['soft'] &&
                        !empty($message)
                    ) {
                        $this->getSession()->setVariable($this->_paymentId . '_error_id', $paymentMethodIds[$this->_paymentId]['soft']);
                    } else {
                        $this->getSession()->setVariable($this->_paymentId . '_error_id', $paymentMethodIds[$this->_paymentId]['denied']);

                    }
                    oxRegistry::getUtils()->redirect($this->getConfig()->getSslShopUrl() . 'index.php?cl=payment', false);
                }

                try {
                    $oOrder = oxNew('oxorder');
                    // finalizing ordering process (validating, storing order into DB, executing payment, setting status ...)
                    $iSuccess = $oOrder->finalizeOrder($oBasket, $oUser);

                    // performing special actions after user finishes order (assignment to special user groups)
                    $oUser->onOrderExecute($oBasket, $iSuccess);

                    if ($oBasket->getOrderId() != null && $oBasket->getOrderId() != $this->getSession()->getVariable('pi_ratepay_shops_order_id')) {
                        $this->getSession()->setVariable('pi_ratepay_shops_order_id', $oBasket->getOrderId());
                    }

                    $this->_saveRatepayOrder($this->getSession()->getVariable('pi_ratepay_shops_order_id'));
                    $tid = $this->getSession()->getVariable($this->_paymentId . '_trans_id');

                    $orderLogs = pi_ratepay_LogsService::getInstance()->getLogsList("transaction_id = " . oxDb::getDb(true)->quote($tid));
                    foreach ($orderLogs as $log) {
                        $log->assign(array('order_number' => $this->getSession()->getVariable('pi_ratepay_shops_order_id')));
                        $log->save();
                    }

                    $this->_ratepayPaymentType = $this->getBasket()->getPaymentId();

                    // proceeding to next view
                    return $this->_getNextStep($iSuccess);
                } catch (oxOutOfStockException $oEx) {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false, true, 'basket');
                } catch (oxNoArticleException $oEx) {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
                } catch (oxArticleInputException $oEx) {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
                }

            }
        } else {
            return parent::execute();
        }
    }

    /**
     * Check if this is a OXID 4.6.x Shop.
     * @return bool
     */
    public function piIsFourPointSixShop()
    {
        return substr(oxRegistry::getConfig()->getVersion(), 0, 3) === '4.6';
    }


    /**
     * Do init payment request redirect to order on succes, on failure redirect back to payment.
     */
    private function _ratepayInitRequest()
    {
        $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId);

        $modelFactory = new ModelFactory();
        $modelFactory->setSandbox($this->_isSandbox(pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId)));
        $modelFactory->setPaymentType($this->_paymentId);
        $payInit = $modelFactory->doOperation('PAYMENT_INIT', array());

        $name = $this->getUser()->oxuser__oxfname->value;
        $surname = $this->getUser()->oxuser__oxlname->value;

        $transactionId = '';

        pi_ratepay_LogsService::getInstance()->logRatepayTransaction('', $transactionId, $paymentMethod, 'PAYMENT_INIT', '', $name, $surname, $payInit);

        if ($payInit->isSuccessful()) {
            $transactionId = (string)$payInit->getTransactionId();
            $this->getSession()->setVariable($this->_paymentId . '_trans_id', (string)$transactionId);
            //$this->getSession()->setVariable($this->_paymentId . '_trans_short_id', (string)$payInit->getTransactionShortId());

            return true;
        } elseif ($payInit->getReasonCode() != 703) {
            $this->getSession()->setVariable($this->_paymentId . '_error_id', "-418");
        } else {
            $this->getSession()->setVariable($this->_paymentId . '_error_id', "-400");
        }

        return false;
    }

    /**
     * Do RatePAY PAYMENT_REQUEST
     *
     * Checks if RatePAY Modul is set to sandbox if true sets the pi_ratepay_xml_service->live to false.
     * Creates request object (type: SimpleXMLExtended) for payment request. Sends the request with
     * pi_ratepay_xml_service::paymentOperation. On success sets descriptor from response to session and returns true.
     * On request failure just returns false. In either case does it log transaction to ratepay log table
     * (pi_ratepay_logging::logRatepay).
     *
     * @uses function _setRatepayHead to generate request head
     * @uses function _setRatepayContent to generate request body
     * @return boolean
     */
    private function _ratepayRequest()
    {

        $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentId);

        $modelFactory = new ModelFactory();
        $modelFactory->setSandbox($this->_isSandbox($paymentMethod));
        $modelFactory->setPaymentType($this->_paymentId);
        $modelFactory->setBasket($this->getBasket());
        $modelFactory->setTransactionId($this->getSession()->getVariable($this->_paymentId . '_trans_id'));
        $modelFactory->setCustomerId($this->getUser()->oxuser__oxcustnr->value);
        $modelFactory->setDeviceToken($this->getSession()->getVariable('pi_ratepay_dfp_token'));

        $payRequest = $modelFactory->doOperation('PAYMENT_REQUEST', array());

        $transactionId = $this->getSession()->getVariable($this->_paymentId . '_trans_id');
        $paymentRequestType = 'PAYMENT_REQUEST';

        $name = $this->getUser()->oxuser__oxfname->value;
        $surname = $this->getUser()->oxuser__oxlname->value;

        pi_ratepay_LogsService::getInstance()->logRatepayTransaction('', $transactionId, $paymentMethod, $paymentRequestType, '', $name, $surname, $payRequest);

        if ($payRequest->isSuccessful()) {

            $descriptor = (string)$payRequest->getDescriptor();
            $this->getSession()->setVariable($this->_paymentId . '_descriptor', $descriptor);
            return true;
        } elseif ($payRequest->getResultCode() == 150) {
            $vars = $payRequest->getCustomerMessage();
            $this->getSession()->setVariable($this->_paymentId . '_error_id', "-001");
            $this->getSession()->setVariable($this->_paymentId . '_message', (string)$vars['customer-message']);
        } else {
            $this->getSession()->setVariable($this->_paymentId . '_error_id', "-418");
        }

        return false;
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
        $transshortid = $this->getSession()->getVariable($this->_paymentId . '_trans_short_id');
        $descriptor = $this->getSession()->getVariable($this->_paymentId . '_descriptor');
        $userbirthdate = $this->getUser()->oxuser__oxbirthdate->value;

        $ratepayOrder = oxNew('pi_ratepay_orders');
        $ratepayOrder->loadByOrderNumber($id);

        $ratepayOrder->assign(array(
            'order_number' => $id,
            'transaction_id' => $transid,
            'transaction_short_id' => $transshortid,
            'descriptor' => $descriptor,
            'userbirthdate' => $userbirthdate
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
        $articles = $this->getBasket()->getContents();

        foreach ($articles as $article) {
            $articlenumber = $article->getArticle()->getId();
            $quantity = $article->getAmount();
            $this->_saveToRatepayOrderDetails($id, $articlenumber, $quantity);
        }

        $specialItems = array('oxwrapping', 'oxgiftcard', 'oxdelivery', 'oxpayment', 'oxtsprotection');

        foreach ($specialItems as $articleNumber) {
            $this->_checkBasketCosts($id, $articleNumber);
        }

        if ($this->getBasket()->getVouchers()) {
            foreach ($this->getBasket()->getVouchers() as $voucher) {
                $articlenumber = $voucher->sVoucherId;
                $quantity = 1;
                $this->_saveToRatepayOrderDetails($id, $articlenumber, $quantity);
            }
        }

        if ($this->getBasket()->getDiscounts()) {
            foreach ($this->getBasket()->getDiscounts() as $discount) {
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
        $articlePrice = $this->getBasket()->getCosts($articleNumber);
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
            'price' => $price,
            'vat' => $vat,
            'ordered' => $quantity
        ));

        $ratepayOrderDetails->save();
    }

    /**
     * Get RatePAY Request object.
     * @return  pi_ratepay_ratepayrequest
     */
    protected function _getRatepayRequestOLD($paymentType)
    {
        $requestDataProvider = oxNew('pi_ratepay_requestdatafrontend', $paymentType);
        $ratepayRequest = oxNew('pi_ratepay_ratepayrequest', $this->_selectedPaymentMethod, $requestDataProvider, null, array('country' => pi_ratepay_util_utilities::getCountry($this->getUser()->oxuser__oxcountryid->value)));

        return $ratepayRequest;
    }

    /**
     * Get Ratepay Request object.
     * @param string $paymentType
     * @param oxbasket $basket
     * @return pi_ratepay_ratepayrequest
     */
    protected function _getRatepayRequest($paymentType, $basket = null)
    {
        $requestDataProvider = oxNew('pi_ratepay_requestdatafrontend', $paymentType, $basket);
        return oxNew('pi_ratepay_ratepayrequest', $paymentType, $requestDataProvider);
    }

    /**
     * Do RatePAY PAYMENT_CONFIRM
     *
     * Creates request object (type: SimpleXMLExtended) for payment request. Sends the request with
     * pi_ratepay_xml_service::paymentOperation and logs transaction.
     *
     * @uses  function _setRatepayHead
     */
    private function _ratepayConfirm()
    {
        $ratepayRequest = $this->_getRatepayConfirm();

        $name = $this->getUser()->oxuser__oxfname->value;
        $surname = $this->getUser()->oxuser__oxlname->value;

        $confirmPayment = $ratepayRequest->confirmPayment();

        pi_ratepay_LogsService::getInstance()->logRatepayTransaction($this->_oBasket->getOrderId(), $this->getSession()->getVariable($this->_ratepayPaymentType . '_trans_id'), pi_ratepay_util_utilities::getPaymentMethod($this->_ratepayPaymentType), 'PAYMENT_CONFIRM', '', $confirmPayment['request'], $name, $surname, $confirmPayment['response']);

        if ($confirmPayment['response'] && ((string) $confirmPayment['response']->head->processing->status->attributes()->code) == "OK" && ((string) $confirmPayment['response']->head->processing->result->attributes()->code) == "400") {
            return true;
        }

        return false;
    }

    /**
     * Get RatePAY Request object.
     * @return  pi_ratepay_ratepayrequest
     */
    protected function _getRatepayConfirm()
    {
        $requestDataProvider = oxNew('pi_ratepay_requestdatafrontend', $this->_ratepayPaymentType, $this->getBasket());
        $ratepayRequest = oxNew('pi_ratepay_ratepayrequest', $this->_ratepayPaymentType, $requestDataProvider);

        return $ratepayRequest;
    }

    protected function _isSandbox($method)
    {
        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType(strtolower($method), oxRegistry::getSession()->getVariable('shopId'));
        return ($settings->pi_ratepay_settings__sandbox->rawValue);
    }
}

