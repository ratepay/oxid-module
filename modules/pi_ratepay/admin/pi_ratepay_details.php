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
 * RatePay order admin panel
 * {@inheritdoc}
 *
 * @package   PayIntelligent_RatePAY
 * @extends oxAdminDetails
 */
class pi_ratepay_Details extends oxAdminDetails
{

    /**
     * Unique Order ID
     *
     * @var string
     */
    private $orderId = null;

    /**
     * Amount of the Goodwill
     *
     * @var double
     */
    private $piRatepayVoucher = null;

    /**
     * Database Table name used for Order details
     *
     * @var string
     */
    private $pi_ratepay_order_details;

    /**
     * Type of the Order rate/rechnung
     *
     * @var string
     */
    private $_paymentMethod;

    /**
     * shopId
     *
     * @var int
     */
    private $_shopId;

    /**
     * Order Model Object
     * An representation of the order whicht get edited.
     *
     * @var oxOrder
     */
    private $_oEditObject = null;

    /**
     *
     * @var mixed
     */
    private $_paymentSid;

    /**
     * request data backend object, get User Data.
     *
     * @var pi_ratepay_requestdatabackend
     */
    private $_requestDataBackend;

    /**
     * Is shop set to UTF8 Mode
     * @var bool
     */
    private $_utfMode = null;

    private $_transactionId;

    /**
     * Preparing all necessary Data for rendering and executing all calls
     * also: {@inheritdoc}
     *
     * @see oxAdminDetails::render()
     * @return string
     */
    public function render()
    {
        parent::render();

        $order = $this->getEditObject();

        $paymentSid = $this->_getPaymentSid();

        if ($paymentSid && in_array($paymentSid, pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD)) {
            $this->_initRatepayDetails($order);
            return "pi_ratepay_details.tpl";
        }

        return "pi_ratepay_no_details.tpl";
    }

    /**
     * Initialises smarty variables specific to RatePAY order.
     * @param oxorder $order
     */
    private function _initRatepayDetails(oxOrder $order)
    {

        $this->_paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_getPaymentSid());
        $this->_shopId = $this->getConfig()->getShopId();
        $this->_shopId = oxNew('pi_ratepay_settings')->setShopIdToOne($this->_shopId);


        $this->pi_ratepay_order_details = 'pi_ratepay_order_details';

        $this->_requestDataBackend = oxNew('pi_ratepay_requestdatabackend', $this->getEditObject());

        $ratepayOrder = oxNew('pi_ratepay_orders');
        $ratepayOrder->loadByOrderNumber($this->_getOrderId());
        $this->_transactionId = $ratepayOrder->pi_ratepay_orders__transaction_id->rawValue;
        $transactionId = $ratepayOrder->pi_ratepay_orders__transaction_id->rawValue;
        $descriptor = $ratepayOrder->pi_ratepay_orders__descriptor->rawValue;
        $this->addTplParam('pi_transaction_id', $transactionId);
        $this->addTplParam('pi_descriptor', $descriptor);

        $this->addTplParam('pi_total_amount', $order->oxorder__oxtotalordersum->getRawValue());

        $this->addTplParam('pi_ratepay_payment_type', $this->_paymentMethod);
        $this->addTplParam('articleList', $this->getPreparedOrderArticles());
        $this->addTplParam('historyList', $this->getHistory($this->_aViewData["articleList"]));

        if ($this->_getPaymentSid() == "pi_ratepay_rate") {
            $ratepayRateDetails = oxNew('pi_ratepay_ratedetails');
            $ratepayRateDetails->loadByOrderId($this->_getOrderId());

            $pirptotalamountvalue = $ratepayRateDetails->pi_ratepay_rate_details__totalamount->rawValue;
            $pirpamountvalue = $ratepayRateDetails->pi_ratepay_rate_details__amount->rawValue;
            $pirpinterestamountvalue = $ratepayRateDetails->pi_ratepay_rate_details__interestamount->rawValue;
            $pirpservicechargevalue = $ratepayRateDetails->pi_ratepay_rate_details__servicecharge->rawValue;
            $pirpannualpercentageratevalue = $ratepayRateDetails->pi_ratepay_rate_details__annualpercentagerate->rawValue;
            $pirpdebitinterestvalue = $ratepayRateDetails->pi_ratepay_rate_details__monthlydebitinterest->rawValue;
            $pirpnumberofratesvalue = $ratepayRateDetails->pi_ratepay_rate_details__numberofrates->rawValue;
            $pirpratevalue = $ratepayRateDetails->pi_ratepay_rate_details__rate->rawValue;
            $pirplastratevalue = $ratepayRateDetails->pi_ratepay_rate_details__lastrate->rawValue;

            $pirptotalamountvalue = str_replace(".", ",", $this->_getFormattedNumber($pirptotalamountvalue)) . " EUR";
            $pirpamountvalue = str_replace(".", ",", $this->_getFormattedNumber($pirpamountvalue)) . " EUR";
            $pirpinterestamountvalue = str_replace(".", ",", $this->_getFormattedNumber($pirpinterestamountvalue)) . " EUR";
            $pirpservicechargevalue = str_replace(".", ",", $this->_getFormattedNumber($pirpservicechargevalue)) . " EUR";
            $pirpannualpercentageratevalue = str_replace(".", ",", $this->_getFormattedNumber($pirpannualpercentageratevalue)) . "%";
            $pirpdebitinterestvalue = str_replace(".", ",", $this->_getFormattedNumber($pirpdebitinterestvalue)) . "%";
            $pirpnumberofratesvalue = str_replace(".", ",", $this->_getFormattedNumber($pirpnumberofratesvalue)) . " Monate";
            $pirpratevalue = str_replace(".", ",", $this->_getFormattedNumber($pirpratevalue)) . " EUR";
            $pirplastratevalue = str_replace(".", ",", $this->_getFormattedNumber($pirplastratevalue)) . " EUR";

            $this->addTplParam('pirptotalamountvalue', $pirptotalamountvalue);
            $this->addTplParam('pirpamountvalue', $pirpamountvalue);
            $this->addTplParam('pirpinterestamountvalue', $pirpinterestamountvalue);
            $this->addTplParam('pirpservicechargevalue', $pirpservicechargevalue);
            $this->addTplParam('pirpannualpercentageratevalue', $pirpannualpercentageratevalue);
            $this->addTplParam('pirpmonthlydebitinterestvalue', $pirpdebitinterestvalue);
            $this->addTplParam('pirpnumberofratesvalue', $pirpnumberofratesvalue);
            $this->addTplParam('pirpratevalue', $pirpratevalue);
            $this->addTplParam('pirplastratevalue', $pirplastratevalue);
        }
    }

    /**
     * init RatePay data, start deliver request
     */
    public function deliver()
    {
        $this->_initRatepayDetails($this->getEditObject());
        $this->deliverRequest();
    }

    /**
     * init RatePay data, start paymentChangeRequest
     */
    public function cancel()
    {
        $this->_initRatepayDetails($this->getEditObject());
        $this->paymentChangeRequest('cancellation');
    }

    /**
     * init RatePay data, start paymentChangeRequest
     */
    public function retoure()
    {
        $this->_initRatepayDetails($this->getEditObject());
        $this->paymentChangeRequest('return');
    }

    /**
     * init RatePay data, start credit request
     *
     * @return null
     */
    public function credit()
    {
        $voucherAmount = oxRegistry::getConfig()->getRequestParameter('voucherAmount');
        $voucherKomma = oxRegistry::getConfig()->getRequestParameter('voucherAmountKomma');

        $this->_initRatepayDetails($this->getEditObject());

        if (isset($voucherAmount) && preg_match("/^[0-9]{1,4}$/", $voucherAmount)) {
            $voucherKomma = isset($voucherKomma) && preg_match('/^[0-9]{1,2}$/', $voucherKomma)? $voucherKomma : '00';

            $voucherAmount .= '.' . $voucherKomma;
            $voucherAmount = (double) $voucherAmount;

            if ($voucherAmount <= $this->getEditObject()->getTotalOrderSum() && $voucherAmount > 0) {
                $this->piRatepayVoucher = $voucherAmount;

                $this->creditRequest();
                return;
            }
        }

        $this->addTplParam('pierror', 'credit');
    }

    /**
     * Gets the History of the order
     *
     * @param array articleList
     * @return string
     */
    private function getHistory($articleList)
    {
        $ratepayHistoryList = oxNew('pi_ratepay_historylist');
        $ratepayHistoryList->getFilteredList("order_number = '" . $this->_getOrderId() . "'");

        $historyList = array();

        foreach ($ratepayHistoryList as $historyItem) {
            $title = '';
            $articleNumber = '';

            foreach ($articleList as $article) {
                if ($historyItem->pi_ratepay_history__article_number->rawValue == $article['artid']) {
                    $title = $article['title'];
                    $articleNumber = $article['artnum'];
                }
            }

            array_push($historyList, array(
                'article_number' => $articleNumber,
                'title'          => $title,
                'quantity'       => $historyItem->pi_ratepay_history__quantity->rawValue,
                'method'         => $historyItem->pi_ratepay_history__method->rawValue,
                'subtype'        => $historyItem->pi_ratepay_history__submethod->rawValue,
                'date'           => $historyItem->pi_ratepay_history__date->rawValue
            ));
        }

        return $historyList;
    }

    /**
     * Gets all articles with additional informations
     *
     * @return array
     */
    public function getPreparedOrderArticles()
    {
        $detailsViewData = oxNew('pi_ratepay_detailsviewdata', $this->_getOrderId());

        return $detailsViewData->getPreparedOrderArticles();
    }

    /**
     * add new voucher for order
     *
     * @return string oxId of voucher
     */
    private function piAddVoucher()
    {
        $order = $this->getEditObject();
        $orderId = $this->_getOrderId();
        $oArticles = $this->getPreparedOrderArticles();

        $voucherCount = oxDb::getDb()->getOne("SELECT count( * ) AS nr FROM `oxvouchers`	WHERE oxvouchernr LIKE 'pi-Merchant-Voucher-%'");
        $voucherNr = "pi-Merchant-Voucher-" . $voucherCount;

        $newVoucher = oxNew("oxvoucher");
        $newVoucher->assign(array(
            'oxvoucherserieid' => 'Anbieter Gutschrift',
            'oxorderid' => $orderId,
            'oxuserid' => $order->getFieldData("oxuserid"),
            'oxdiscount' => $this->piRatepayVoucher,
            'oxdateused' => date('Y-m-d', oxRegistry::get("oxUtilsDate")->getTime()),
            'oxvouchernr' => $voucherNr
        ));

        $newVoucher->save();
        $this->_recalculateOrder($order, $oArticles, $voucherNr);

        $tmptotal = 0;
        foreach ($oArticles as $article){
            if($article['amount'] > 0){
                $tmptotal += $article['amount'] * $article['bruttoprice'];
            }
        }

        $voucherId = $newVoucher->getId();

        $voucherDetails = oxNew('pi_ratepay_orderdetails');

        $voucherDetails->assign(array(
            'order_number' => $orderId,
            'article_number' => $voucherId,
            'ordered' => 1,
        ));
        if ($tmptotal < $this->piRatepayVoucher){
            $voucherDetails->assign(array(
                'shipped' => 1,
            ));
        }

        $voucherDetails->save();

        return $voucherId;
    }

    /**
     * Do RatePay request. If the request succeeds add voucher to order and log to history.
     */
    protected function creditRequest()
    {
        $operation = "PAYMENT_CHANGE";
        $subtype = "credit";
        $nr = oxDb::getDb()->getOne("SELECT count( * ) AS nr FROM `oxvouchers` WHERE oxvouchernr LIKE 'pi-Merchant-Voucher-%'");
        $vouchertitel = "pi-Merchant-Voucher-" . $nr;

        $articles[] = array(
            'title'     => 'Credit',
            'artnum'    => $vouchertitel,
            'unitprice' => "-" . $this->_getFormattedNumber($this->piRatepayVoucher),
            'arthash'   => 1,
            'vat'       => 0,
        );

        $modelFactory = new ModelFactory();
        $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentSid);
        $modelFactory->setSandbox($this->_isSandbox($paymentMethod));
        $modelFactory->setPaymentType($this->_getPaymentSid());
        $modelFactory->setShopId($this->_shopId);
        $modelFactory->setBasket($articles);
        $modelFactory->setTransactionId($this->_transactionId);
        $modelFactory->setOrderId($this->_getOrderId());
        $modelFactory->setSubtype($subtype);
        $change = $modelFactory->doOperation($operation);

        $isSuccess = 'pierror';
        if ($change->isSuccessful()) {
            $artid = $this->piAddVoucher();
            $this->_logHistory($this->_getOrderId(), $artid, 1, $operation, $subtype);

            $isSuccess = 'pisuccess';
        }
        $this->addTplParam($isSuccess, $subtype);
    }

    /**
     * Excecute payment change request. If the request succeeds add voucher to order and log to history.
     * @param string $paymentChangeType 'cancel' or 'return
     */
    protected function paymentChangeRequest($paymentChangeType)
    {
        $operation = 'PAYMENT_CHANGE';
        $modelFactory = new ModelFactory();
        $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentSid);
        $modelFactory->setSandbox($this->_isSandbox($paymentMethod));
        $modelFactory->setPaymentType($this->_getPaymentSid());
        $modelFactory->setShopId($this->_shopId);
        $articles = $this->getPreparedOrderArticles();
        $modelFactory->setBasket($articles);
        $modelFactory->setTransactionId($this->_transactionId);
        $modelFactory->setOrderId($this->_getOrderId());
        $modelFactory->setSubtype($paymentChangeType);
        $change = $modelFactory->doOperation($operation);

        $isSuccess = 'pierror';
        if ($change->isSuccessful()) {
            $articles = $this->getPreparedOrderArticles();
            $articleList = array();
            foreach ($articles as $article) {
                if (oxRegistry::getConfig()->getRequestParameter($article['arthash']) > 0) {
                    $quant = oxRegistry::getConfig()->getRequestParameter($article['arthash']);
                    $artid = $article['artid'];
                    if ($paymentChangeType == "cancellation") {
                        oxDb::getDb()->execute("update $this->pi_ratepay_order_details set cancelled=cancelled+$quant where order_number='" . $this->_getOrderId() . "' and article_number='$artid'");
                    } else if ($paymentChangeType == "return") {
                        oxDb::getDb()->execute("update $this->pi_ratepay_order_details set returned=returned+$quant where order_number='" . $this->_getOrderId() . "' and article_number='$artid'");
                    }
                    $this->_logHistory($this->_getOrderId(), $artid, $quant, $operation, $paymentChangeType);
                    if ($article['oxid'] != "") {
                        $articleList[$article['oxid']] = array('oxamount' => $article['ordered'] - $article['cancelled'] - $article['returned'] - oxRegistry::getConfig()->getRequestParameter($article['arthash']));
                    } else {
                        $oOrder = $this->getEditObject();

                        if ($article['artid'] == "oxdelivery") {
                            $oOrder->oxorder__oxdelcost->setValue(0);
                        } else if ($article['artid'] == "oxpayment") {
                            $oOrder->oxorder__oxpaycost->setValue(0);
                        } else if ($article['artid'] == "oxwrapping") {
                            $oOrder->oxorder__oxwrapcost->setValue(0);
                        }else if ($article['artid'] == "oxgiftcard") {
                                $oOrder->oxorder__oxgiftcardcost->setValue(0);
                        }  else if ($article['artid'] == "oxtsprotection") {
                            $oOrder->oxorder__oxtsprotectcosts->setValue(0);
                        } else if ($article['artid'] == "discount") {
                            $oOrder->oxorder__oxdiscount->setValue(0);
                        }else {
                            $value = $oOrder->oxorder__oxvoucherdiscount->getRawValue() + $article['totalprice'];
                            $oOrder->oxorder__oxvoucherdiscount->setValue($value);
                        }
                    }
                }
            }
            $this->updateOrder($articleList, $this->_isPaymentChangeFull());
            $isSuccess = 'pisuccess';
        }

        if ($this->_isPaymentChangeFull()) {
            $paymentChangeType = 'full-' . $paymentChangeType;
        } else {
            $paymentChangeType = 'partial-' . $paymentChangeType;
        }

        $this->addTplParam($isSuccess, $paymentChangeType);
    }

    /**
     * Tests if all available articles are returned or cancelled.
     * @return boolean
     */
    protected function _isPaymentChangeFull()
    {
        $full = true;
        $articles = $this->getPreparedOrderArticles();

        foreach ($articles as $article) {
            if (oxRegistry::getConfig()->getRequestParameter($article['arthash']) != $article['ordered']) {
                $full = false;
            }
        }

        return $full;
    }

    protected function _isSandbox($method)
    {
        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType(strtolower($method), $this->_shopId);
        return ($settings->pi_ratepay_settings__sandbox->rawValue);
    }

    /**
     * Excecute payment change request. If the request succeeds add voucher to order and log to history.
     */
    protected function deliverRequest()
    {
        $operation = 'CONFIRMATION_DELIVER';
        $modelFactory = new ModelFactory();
        $paymentMethod = pi_ratepay_util_utilities::getPaymentMethod($this->_paymentSid);
        $modelFactory->setSandbox($this->_isSandbox($paymentMethod));
        $modelFactory->setPaymentType($this->_getPaymentSid());
        $modelFactory->setShopId($this->_shopId);
        $articles = $this->getPreparedOrderArticles();
        $modelFactory->setBasket($articles);
        $modelFactory->setTransactionId($this->_transactionId);
        $modelFactory->setOrderId($this->_getOrderId());

        $deliver = $modelFactory->doOperation($operation);

        $isSuccess = 'pierror';

        if ($deliver->isSuccessful()) {
            $articles = $this->getPreparedOrderArticles();
            foreach ($articles as $article) {
                if (oxRegistry::getConfig()->getRequestParameter($article['arthash']) > 0) {
                    $quant = oxRegistry::getConfig()->getRequestParameter($article['arthash']);
                    $artid = $article['artid'];
                    // @todo this can be done better
                    oxDb::getDb()->execute("update $this->pi_ratepay_order_details set shipped=shipped+$quant where order_number='" . $this->_getOrderId() . "' and article_number='$artid'");
                    $this->_logHistory($this->_getOrderId(), $artid, $quant, $operation, '');
                }
            }
            $isSuccess = 'pisuccess';
        }

        $this->addTplParam($isSuccess, '');
    }

    /**
     * logs ratepay backend transactions history.
     *
     * @param string $orderId oxid of the order
     * @param string $artid oxid of the article which is modified
     * @param string $quant quantity which is changed
     * @param string $operation (deliver, payment change, credit)
     * @param string $subtype (cancellation, return)
     */
    protected function _logHistory($orderId, $artid, $quant, $operation, $subtype)
    {
        $ratepayHistory = oxNew('pi_ratepay_history');
        $ratepayHistory->assign(array(
            'order_number'   => $orderId,
            'article_number' => $artid,
            'quantity'       => $quant,
            'method'         => $operation,
            'submethod'      => $subtype,
            'date'           => date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime())
        ));
        $ratepayHistory->save();
    }

    /**
     * Updates order articles stock and recalculates order
     *
     * @return null
     */
    public function updateOrder($articleList, $fullCancellation)
    {
        $aOrderArticles = $articleList;
        $oArticles = $this->getPreparedOrderArticles();

        if (is_array($aOrderArticles) && $oOrder = $this->getEditObject()) {

            $myConfig = $this->getConfig();
            $oOrderArticles = $oOrder->getOrderArticles();
            $blUseStock = $myConfig->getConfigParam('blUseStock');
            if ($fullCancellation) {
                $oOrder->oxorder__oxstorno = new oxField(1);
               }

            $oOrder->save();

            foreach ($oOrderArticles as $oOrderArticle) {
                $sItemId = $oOrderArticle->getId();
                if (isset($aOrderArticles[$sItemId])) {

                    // update stock
                    if ($blUseStock) {
                        $oOrderArticle->setNewAmount($aOrderArticles[$sItemId]['oxamount']);
                    } else {
                        $oOrderArticle->assign($aOrderArticles[$sItemId]);
                        $oOrderArticle->save();
                    }
                    if ($aOrderArticles[$sItemId]['oxamount'] == 0) {
                        $this->storno($sItemId);
                    }
                }
            }
            // recalculating order
            $this->_recalculateOrder($oOrder, $oArticles);
        }
    }

    /**
     * cancels order item
     *
     * @param string $sItemId
     */
    public function storno($sItemId)
    {
        $myConfig = $this->getConfig();

        $sOrderArtId = $sItemId;
        $oArticle = oxNew('oxorderarticle');
        $oArticle->load($sOrderArtId);

        $oArticle->oxorderarticles__oxstorno->setValue(1);

        // stock information
        if ($myConfig->getConfigParam('blUseStock')) {
            $oArticle->updateArticleStock($oArticle->oxorderarticles__oxamount->value, $myConfig->getConfigParam('blAllowNegativeStock'));
        }

        $oDb = oxDb::getDb();
        $sQ = "update oxorderarticles set oxstorno = " . $oDb->quote($oArticle->oxorderarticles__oxstorno->value) . " where oxid =" . $oDb->quote($sOrderArtId);
        $oDb->execute($sQ);
    }

    /**
     * Returns editable order object
     *
     * @return oxorder
     */
    public function getEditObject()
    {
        $orderId = $this->_getOrderId();
        if ($this->_oEditObject === null && isset($orderId) && $orderId != "-1") {
            $this->_oEditObject = oxNew("oxorder");
            $this->_oEditObject->load($orderId);
        }
        return $this->_oEditObject;
    }

    protected function _getOrderId()
    {
        if ($this->orderId === null) {
            $this->orderId = $this->getEditObjectId();
        }

        return $this->orderId;
    }

    /**
     * Call to order object to recalculateOrder
     *
     * @param oxorder $oOrder
     */
    private function _recalculateOrder($oOrder, $aOrderArticles, $voucherNr = null)
    {
        // keeps old delivery cost
        $oOrder->reloadDiscount(false);
        $oOrder->reloadDelivery(false);
        $oDb = oxDb::getDb();

            $totalprice = 0;

            foreach($aOrderArticles as $article){
                $totalprice += $article['totalprice'];
                $oxnprice = $article['unitprice'] * $article['amount'];
                $oxbprice = ($oxnprice * ($article['vat'] + 100)) / 100;
                $oDb->execute("update oxorderarticles set oxnetprice ='" . $oxnprice . "', oxbrutprice = '". $oxbprice ."' where oxartid = '" . $article['artid'] ."' and oxorderid = " . $oDb->quote($oOrder->oxorder__oxid->getRawValue()));
            }

            if($voucherNr != null){
                $discount = (float) $oDb->getOne("select oxdiscount from oxvouchers where oxvouchernr = '" . $voucherNr . "'");
                $tDiscount = $oOrder->oxorder__oxvoucherdiscount->getRawValue();
                $tDiscount += $discount;
                $sQ = "update oxorder set oxvoucherdiscount ='" . $tDiscount . "'where oxid=" . $oDb->quote($oOrder->oxorder__oxid->getRawValue());
                $oDb->execute($sQ);
                $totalprice -= $discount;
            }

            if($totalprice < 0){
                $totalprice = 0;
            }

            $oxnprice = $oDb->getOne("select sum(oxnetprice) from oxorderarticles where oxorderid=" . $oDb->quote($oOrder->oxorder__oxid->getRawValue()));
            $oxbprice = $oDb->getOne("select sum(oxbrutprice) from oxorderarticles where oxorderid=" . $oDb->quote($oOrder->oxorder__oxid->getRawValue()));

            $sQ = "update oxorder set oxtotalordersum = '" . $totalprice . "', oxtotalnetsum ='" . $oxnprice . "', oxtotalbrutsum ='" . $oxbprice . "'  where oxid = " . $oDb->quote($oOrder->oxorder__oxid->getRawValue());
            $oDb->execute($sQ);
            $oOrder->oxorder__oxtotalordersum->setValue($totalprice);
            $oOrder->oxorder__oxtotalnetsum->setValue($oxnprice);
            $oOrder->oxorder__oxtotalbrutsum->setValue($oxbprice);
    }

    /**
     * Return payment type used in order.
     * @return string
     */
    protected function _getPaymentSid()
    {
        if ($this->_paymentSid === null) {
            $order = $this->getEditObject();
            $this->_paymentSid = isset($order)? $order->getPaymentType()->oxuserpayments__oxpaymentsid->value : false;
        }
        return $this->_paymentSid;
    }

    /**
     * Get formattet number
     * @param string $str
     * @param int $decimal
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    private function _getFormattedNumber($str, $decimal = 2, $dec_point = ".", $thousands_sep = "")
    {
        $util = new pi_ratepay_util_Utilities();
        return $util->getFormattedNumber($str, $decimal, $dec_point, $thousands_sep);
    }

}
