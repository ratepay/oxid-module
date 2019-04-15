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
class ModelFactory extends oxSuperCfg {

    protected $_orderId;

    protected $_countryCode;

    protected $_securityCode;

    protected $_profileId;

    protected $_sandbox;

    protected $_paymentType;

    protected $_basket;

    protected $_transactionId;

    protected $_deviceToken;

    protected $_customerId;

    protected $_subtype;

    protected $_shopId;

    protected $_countryId;

    protected $_calculationData = array();

    /**
     * @param mixed $subtype
     */
    public function setSubtype($subtype)
    {
        $this->_subtype = $subtype;
    }

    /**
     * @param mixed $shopId
     */
    public function setShopId($shopId)
    {
        $this->_shopId = $shopId;
    }

    /**
     * @param mixed $countryId
     */
    public function setCountryId($countryId)
    {
        $this->_countryId = $countryId;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->_customerId = $customerId;
    }


    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->_orderId = $orderId;
    }

    /**
     * @param mixed $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->_transactionId = $transactionId;
    }

    /**
     * @param mixed $deviceToken
     */
    public function setDeviceToken($deviceToken)
    {
        $this->_deviceToken = $deviceToken;
    }

    /**
     * @param mixed $basket
     */
    public function setBasket($basket)
    {
        $this->_basket = $basket;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->_countryCode = $countryCode;
    }

    /**
     * @param mixed $securityCode
     */
    public function setSecurityCode($securityCode)
    {
        $this->_securityCode = $securityCode;
    }

    /**
     * @param mixed $profileId
     */
    public function setProfileId($profileId)
    {
        $this->_profileId = $profileId;
    }

    /**
     * @param mixed $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->_sandbox = (bool)$sandbox;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->_paymentType = $paymentType;
    }

    /**
     * @param array $calculationData
     */
    public function setCalculationData($calculationData)
    {
        $this->_calculationData = $calculationData;
    }

    /**
     * do operation
     *
     * @param $operation
     * @return bool|mixed|object
     */
    public function doOperation($operation)
    {
        switch ($operation) {
            case 'PAYMENT_INIT':
                return $this->_makePaymentInit();
                break;
            case 'PAYMENT_REQUEST':
                return $this->_makePaymentRequest();
                break;
            case 'PAYMENT_CONFIRM':
                return $this->_makePaymentConfirm();
                break;
            case 'CONFIRMATION_DELIVER':
                return $this->_makeConfirmationDeliver();
                break;
            case 'PAYMENT_CHANGE':
                return $this->_makePaymentChange();
                break;
            case 'PROFILE_REQUEST':
                return $this->_makeProfileRequest();
                break;
            case 'CALCULATION_REQUEST':
                return $this->_makeCalculationRequest();
                break;
        }
    }

    /**
     * Get RatePAY Confirm Settings
     * @return int
     */
    private function _getConfirmSettings()
    {
        $oDb = oxDb::getDb();
        $sqlResult = $oDb->getRow('SELECT * FROM pi_ratepay_global_settings');

        $globalConfig['confirm'] = (bool) $sqlResult[2];

        return $sqlResult[2];
    }

    /**
     * make a payment confirm
     *
     * @return bool
     */
    private function _makePaymentConfirm() {
        $util = new pi_ratepay_util_Utilities();
        $paymentMethod =  $util->getPaymentMethod($this->_paymentType);

        $confirm = $this->_getConfirmSettings();
        if ($confirm == 0) {
            return true;
        }

        $mbHead = $this->_getHead();
        $rb = new RatePAY\RequestBuilder($this->_sandbox);

        $paymentConfirm = $rb->callPaymentConfirm($mbHead);
        pi_ratepay_LogsService::getInstance()->logRatepayTransaction($this->_orderId, $this->_transactionId, $this->_paymentType, 'PAYMENT_CONFIRM', $this->_subtype, '', '', $paymentConfirm);

        if ($paymentConfirm->isSuccessful()) {
            return true;
        }
        return false;
    }

    /**
     * make calculation request
     *
     * @return object
     */
    private function _makeCalculationRequest()
    {
        $mbHead = $this->_getHead();

        $array['InstallmentCalculation']['Amount'] = $this->_calculationData['requestAmount'];
        if ($this->_calculationData['requestSubtype'] == 'calculation-by-rate') {
            $array['InstallmentCalculation']['CalculationRate']['Rate'] = $this->_calculationData['requestValue'];
        } else {
            $array['InstallmentCalculation']['CalculationTime']['Month'] = $this->_calculationData['requestValue'];
        }
        $array['InstallmentCalculation']['PaymentFirstday'] = $this->_calculationData['paymentFirstday'];

        $mbContentTime = new RatePAY\ModelBuilder('Content');
        $mbContentTime->setArray($array);

        $rb = new RatePAY\RequestBuilder($this->_sandbox);
        $calculationRequest = $rb->callCalculationRequest($mbHead, $mbContentTime)->subtype($this->_calculationData['requestSubtype']);
        return $calculationRequest;
    }

    /**
     * make confirmation deliver
     *
     * @return object
     */
    private function _makeConfirmationDeliver()
    {
        $this->_getOrderCountryId();
        $mbHead = $this->_getHead();

        $shoppingBasket = [
            'ShoppingBasket' => $this->_getSpecialBasket(),
        ];

        $mbContent = new RatePAY\ModelBuilder('Content');
        $mbContent->setArray($shoppingBasket);
        //$mbContent->setArray($invoicing);

        $rb = new \RatePAY\RequestBuilder($this->_sandbox);
        $confirmationDeliver = $rb->callConfirmationDeliver($mbHead, $mbContent);
        pi_ratepay_LogsService::getInstance()->logRatepayTransaction($this->_orderId, $this->_transactionId, $this->_paymentType, 'CONFIRMATION_DELIVER', $this->_subtype, '', '', $confirmationDeliver);
        return $confirmationDeliver;
    }

    /**
     * get order country id
     */
    private function _getOrderCountryId() {
        $countryId = oxDb::getDb()->getOne("SELECT OXBILLCOUNTRYID FROM oxorder WHERE OXID = '" . $this->_orderId . "'");
        $this->_countryId = $countryId;
    }

    /**
     * make payment change
     *
     * @return object|bool
     */
    private function _makePaymentChange()
    {
        $this->_getOrderCountryId();
        $mbHead = $this->_getHead();
        $detailsViewData = oxNew('pi_ratepay_detailsviewdata', $this->_orderId);

        $this->basket = $detailsViewData->getPreparedOrderArticles();

        $shoppingBasket = [
            'ShoppingBasket' => $this->_getSpecialBasket(),
        ];

        $mbContent = new RatePAY\ModelBuilder('Content');
        $mbContent->setArray($shoppingBasket);

        $rb = new \RatePAY\RequestBuilder($this->_sandbox);
        $paymentChange = $rb->callPaymentChange($mbHead, $mbContent)->subtype($this->_subtype);
        pi_ratepay_LogsService::getInstance()->logRatepayTransaction($this->_orderId, $this->_transactionId, $this->_paymentType, 'PAYMENT_CHANGE', $this->_subtype, '', '', $paymentChange);
        return $paymentChange;
    }

    /**
     *
     */
    protected function _getSecurityCode()
    {
        $oConfig = $this->getConfig();
    }

    /**
     * return the head for an request
     */
    private function _getHead()
    {
        if ($this->_profileId && $this->_securityCode) {
            $profileId = $this->_profileId;
            $securityCode = $this->_securityCode;
        } else {
            $util = new pi_ratepay_util_Utilities();
            $paymentMethod =  $util->getPaymentMethod($this->_paymentType);
            $paymentMethod = strtolower($paymentMethod);
            $country = $this->_getCountryCodeById($this->_countryId);
            $settings = oxNew('pi_ratepay_settings');
            if ($country) {
                $settings->loadByType($paymentMethod, $this->_shopId, $country);
            } else {
                $settings->loadByType($paymentMethod, $this->_shopId);
            }
            $profileId = $settings->pi_ratepay_settings__profile_id->rawValue;
            $securityCode = $settings->pi_ratepay_settings__security_code->rawValue;
            $this->setSandbox($settings->pi_ratepay_settings__sandbox->rawValue);
        }

        $headArray = [
            'SystemId' => $_SERVER['SERVER_ADDR'],
            'Credential' => [
                'ProfileId' => $profileId,
                'Securitycode' => $securityCode
                ],
            'Meta' => [
                'Systems' => [
                    'System' => [
                        'Name' => 'OXID_' . oxRegistry::getConfig()->getEdition(),
                        'Version' => oxRegistry::getConfig()->getVersion() . '_' . pi_ratepay_util_utilities::PI_MODULE_VERSION
                    ]
                ]
            ]
        ];

        $modelBuilder = new RatePAY\ModelBuilder();

        if (!empty($this->_transactionId)) {
            $modelBuilder->setTransactionId($this->_transactionId);
        }

        $modelBuilder->setArray($headArray);
        if (!empty($this->_orderId)) {
            $orderNr = oxDb::getDb()->getOne('SELECT OXORDERNR FROM oxorder where oxid = ?', array($this->_orderId));
            $external['External']['OrderId'] = $orderNr;
        }
        if (!empty($this->_customerId)) {
            $external['External']['MerchantConsumerId'] = $this->_customerId;
        }

        if (!empty($this->_deviceToken)) {
            $modelBuilder->setCustomerDevice(
                $modelBuilder->CustomerDevice()->setDeviceToken($this->_deviceToken)
            );
        }
        if (!empty($external)) {
            $modelBuilder->setArray($external);
        }

        return $modelBuilder;
    }

    /**
     * make profile request
     *
     * @return bool
     */
    private function _makeProfileRequest()
    {
        $head = $this->_getHead();

        $rb = new \RatePAY\RequestBuilder($this->_sandbox);
        $profileRequest = $rb->callProfileRequest($head);

        if ($profileRequest->isSuccessful()) {
            return $profileRequest->getResult();
        }
        return false;
    }

    /**
     * make payment init
     *
     * @return object|bool
     */
    private function _makePaymentInit()
    {
        $head = $this->_getHead();
        $rb = new \RatePAY\RequestBuilder($this->_sandbox);
        $paymentInit = $rb->callPaymentInit($head);
        pi_ratepay_LogsService::getInstance()->logRatepayTransaction('', '', $this->_paymentType, 'PAYMENT_INIT', '', $this->getUser()->oxuser__oxfname->value, $this->getUser()->oxuser__oxlname->value, $paymentInit);
        return $paymentInit;
    }

    /**
     * make payment request
     *
     * @return mixed
     */
    private function _makePaymentRequest()
    {
        $head = $this->_getHead();
        $basket = $this->_getBasket();
        $util = new pi_ratepay_util_Utilities();

        $salutation = strtoupper($this->getUser()->oxuser__oxsal->value);
        switch ($salutation) {
            default:
                $gender = 'u';
                break;
            case 'MR':
                $gender = 'm';
                break;
            case 'MRS':
                $gender = 'f';
                break;
        }

        if (!empty($this->getUser()->oxuser__oxfon->value)) {
            $phone = $this->getUser()->oxuser__oxfon->value;
        } else {
            $phone = $this->getUser()->oxuser__oxmbfon->value;
        }

        $contentArr = [
            'Customer' => [
                'Gender' => $gender,
                'FirstName' => $this->getUser()->oxuser__oxfname->value,
                'LastName' => $this->getUser()->oxuser__oxlname->value,
                'DateOfBirth' => $this->getUser()->oxuser__oxbirthdate->value,
                'IpAddress' => "127.0.0.1",
                'Addresses' => [
                    [
                        'Address' => $this->_getCustomerAddress()
                    ], [
                        'Address' => $this->_getDeliveryAddress()
                    ]
                ],
                'Contacts' => [
                    'Email' => $this->getUser()->oxuser__oxusername->value,
                    'Phone' => [
                        'DirectDial' => $phone
                    ],
                ],
            ],
            'ShoppingBasket' => $basket,
            'Payment' => [
                'Method' => strtolower($util->getPaymentMethod($this->_paymentType)),
                'Amount' => $this->_basket->getPrice()->getBruttoPrice()
            ]
        ];

        if (!empty('company')) {
            $contentArr['Customer']['CompanyName'] = $this->getUser()->oxuser__oxcompany->value;
            $contentArr['Customer']['VatId'] = $this->getUser()->oxuser__oxustid->value;
        }

        if ($util->getPaymentMethod($this->_paymentType) == 'ELV') {
            $contentArr['Customer']['BankAccount'] = $this->_getCustomerBankdata($this->_paymentType);
        }
        if ($util->getPaymentMethod($this->_paymentType) == 'INSTALLMENT') {
            $contentArr['Payment']['InstallmentDetails'] = $this->_getInstallmentData();
            $contentArr['Payment']['DebitPayType'] = 'BANK-TRANSFER';
            $contentArr['Payment']['Amount'] = $this->getSession()->getVariable('pi_ratepay_rate_total_amount');

            $settings = oxNew('pi_ratepay_settings');
            $iban = $this->getSession()->getVariable('pi_ratepay_rate_bank_iban');
            $settings->loadByType($util->getPaymentMethod('pi_ratepay_rate'), $this->getSession()->getVariable('shopId'));
            if (!empty($iban) && $iban !== 'undefined') {
                $contentArr['Customer']['BankAccount'] = $this->_getCustomerBankdata('pi_ratepay_rate');
                $contentArr['Payment']['DebitPayType'] = 'DIRECT-DEBIT';
            }
        }

        $shippingCosts = $this->_getShippingCosts();
        if (!empty($shippingCosts)) {
            $contentArr['ShoppingBasket']['Shipping'] = $shippingCosts;
        }

        $discount = $this->_getDiscount();
        if (!empty($discount)) {
            $contentArr['ShoppingBasket']['Discount'] = $discount;
        }

        $mbContent = new RatePAY\ModelBuilder('Content');
        $mbContent->setArray($contentArr);

        $rb = new \RatePAY\RequestBuilder($this->_sandbox);

        $paymentRequest = $rb->callPaymentRequest($head, $mbContent);
        pi_ratepay_LogsService::getInstance()->logRatepayTransaction('', $this->_transactionId, $this->_paymentType, 'PAYMENT_REQUEST', '', $this->getUser()->oxuser__oxfname->value, $this->getUser()->oxuser__oxlname->value, $paymentRequest);
        return $paymentRequest;
    }

    /**
     * get shipping costs
     *
     * @return array|bool
     */
    private function _getShippingCosts()
    {
        $basket = $this->_basket;
        if (method_exists($basket, 'getDeliveryCost') && $basket->getDeliveryCost()) {
            $deliveryCosts = $basket->getDeliveryCost()->getPrice();
            $deliveryVat = $basket->getDeliveryCost()->getVat();
        } elseif (method_exists($basket, 'getDeliveryCosts') && $basket->getDeliveryCosts()) {
            $deliveryCosts = $basket->getDeliveryCosts();
            if ($basket->$deliveryCosts() > 0) {
                $deliveryVat = $basket->getDelCostVatPercent();
            } else {
                $deliveryVat = 0;
            }
        } else {
            return false;
        }

        if (empty($deliveryCosts)) {
            return false;
        }
        $shipping = array(
            'Description'       => 'Shipping Costs',
            'UnitPriceGross'    => $deliveryCosts ,
            'TaxRate'           => $deliveryVat,
        );

        return $shipping;
    }

    /**
     * get discount
     *
     * @return array|bool|int
     */
    private function _getDiscount()
    {
        $discount = 0;
        $basket = $this->_basket;
        $util = new pi_ratepay_util_Utilities();

        if ($basket->getTotalDiscount() && $basket->getTotalDiscount()->getBruttoPrice() > 0) {
            $discount = $discount + (float)$util->getFormattedNumber($basket->getTotalDiscount()->getBruttoPrice());
        }

        if (count($basket->getVouchers())) {

            foreach ($basket->getVouchers() as $voucher) {
                $vNr = $voucher->sVoucherId;
                $vNr = $this->_getVoucherTitle($vNr);
                $discount = $discount + (float)$util->getFormattedNumber($voucher->dVoucherdiscount);

            }
        }

        if (empty($discount) || $discount <= 0) {
            return false;
        }

        $discount = array(
            'Description'       => 'Discount ' . $vNr,
            'UnitPriceGross'    => $discount,
            'TaxRate'           => $util->getFormattedNumber("0"),
        );

        return $discount;
    }

    /**
     * get voucher title
     *
     * @param $oxid
     * @return false|string
     */
    private function _getVoucherTitle($oxid)
    {
        $voucher = oxDB::getDb()->getOne("SELECT OXVOUCHERSERIEID FROM oxvouchers WHERE OXID ='" . $oxid . "'");
        return oxDb::getDb()->getOne("SELECT OXSERIENR FROM oxvoucherseries WHERE OXID ='" . $voucher . "'");
    }

    /**
     * get installment data
     * @return array
     */
    private function _getInstallmentData()
    {
        $util = new pi_ratepay_util_Utilities();
        return array(
            'InstallmentNumber'     => $this->getSession()->getVariable('pi_ratepay_rate_number_of_rates'),
            'InstallmentAmount'     => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_rate'), '2', '.'),
            'LastInstallmentAmount' => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_last_rate'),'2', '.'),
            'InterestRate'          => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_interest_rate'), '2', '.')
        );
    }

    /**
     * Get customers bank-data, owner can be retrieved either in session or if not set in $this->getUser().
     * @todo bank data persistence
     * @todo validate if bankdata is in session
     * @return array
     */
    private function _getCustomerBankdata($paymentType)
    {
        $bankData          = array();
        $bankDataType      = $this->getSession()->getVariable($paymentType . '_bank_datatype');
        $bankAccountNumber = $this->getSession()->getVariable($paymentType . '_bank_account_number');
        $bankCode          = $this->getSession()->getVariable($paymentType . '_bank_code');
        $bankIban          = $this->getSession()->getVariable($paymentType . '_bank_iban');

        if ($bankDataType == 'classic') {
            $bankData['BankAccountNumber'] = $bankAccountNumber;
            $bankData['BankCode']          = $bankCode;
        } else {
            $bankData['Iban'] = $bankIban;
        }

        $owner = null;
        if ($this->getSession()->hasVariable($paymentType . '_bank_owner')) {
            $bankData['Owner'] = $this->getSession()->getVariable($paymentType . 'elv_bank_owner');
        } else {
            $bankData['Owner'] = $this->getUser()->oxuser__oxfname->value . ' ' . $this->getUser()->oxuser__oxlname->value;
        }

        return $bankData;
    }

    /**
     * Get complete customer address.
     * @return array
     */
    private function _getCustomerAddress()
    {
        $countryCode = oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $this->getUser()->oxuser__oxcountryid->value . "'");

        $address = array(
            'Type'              => 'billing',
            'Street'            => $this->getUser()->oxuser__oxstreet->value,
            'StreetNumber'      => $this->getUser()->oxuser__oxstreetnr->value,
            'ZipCode'           => $this->getUser()->oxuser__oxzip->value,
            'City'              => $this->getUser()->oxuser__oxcity->value,
            'CountryCode'       => $countryCode
        );

        return $address;
    }

    /**
     * Get complete delivery address.
     * @return array
     */
    private function _getDeliveryAddress()
    {
        $order = oxNew('oxorder');
        $deliveryAddress = $order->getDelAddressInfo();

        if (is_null($deliveryAddress)){
            $address = $this->_getCustomerAddress();
            $address['Type'] = 'delivery';
            $address['FirstName'] = $this->getUser()->oxuser__oxfname->value;
            $address['LastName'] = $this->getUser()->oxuser__oxlname->value;
            return $address;
        }

        $countryCode = oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $deliveryAddress->oxaddress__oxcountryid->value . "'");

        $address = array(
            'Type'         => 'delivery',
            'FirstName'    => $deliveryAddress->oxaddress__oxfname->value,
            'LastName'     => $deliveryAddress->oxaddress__oxlname->value,
            'Street'       => $deliveryAddress->oxaddress__oxstreet->value,
            'StreetNumber' => $deliveryAddress->oxaddress__oxstreetnr->value,
            'ZipCode'      => $deliveryAddress->oxaddress__oxzip->value,
            'City'         => $deliveryAddress->oxaddress__oxcity->value,
            'CountryCode'  => $countryCode
        );

        if (!empty($deliveryAddress->oxaddress__oxcompany->value)) {
            $address['Company'] = $deliveryAddress->oxaddress__oxcompany->value;
        }

        return $address;
    }

    /**
     * get special basket for deliver and change
     *
     * @return array
     */
    private function _getSpecialBasket()
    {
        $shoppingBasket = array();
        $artnr =  array();

        $api = $this->_isNewApi();

        foreach ($this->_basket AS $article) {
            if (oxRegistry::getConfig()->getRequestParameter($article['arthash']) <= 0 && $article['title'] !== 'Credit') {
                continue;
            }
            if ($article['artnum'] == 'oxdelivery') {
                if ($api  == true) {
                    $shoppingBasket['Shipping'] = [
                        'Description' => 'Shipping Costs',
                        'UnitPriceGross' => number_format($article['unitprice'] + ($article['unitprice'] / 100 * $article['vat']), '2', '.', ''),
                        'TaxRate' => $article['vat'],
                    ];
                    continue;
                }
            }

            if (substr($article['artnum'], 0, 7) == 'voucher' || $article['artnum'] == 'discount') {
                if ($api  == true) {
                    if (!empty($shoppingBasket['Discount']['UnitPriceGross'])) {
                        $article['unitprice'] = $article['unitprice'] + $shoppingBasket['Discount']['UnitPriceGross'];
                        $article['oxtitle'] = $shoppingBasket['Discount']['Description'] . '_' . $article['oxtitle'];
                    }
                    $shoppingBasket['Discount'] = [
                        'Description' => $article['oxtitle'],
                        'UnitPriceGross' => $article['unitprice'],
                        'TaxRate' => $article['vat'],
                    ];
                    continue;
                }
            }

            $item = array(
                'Description' => $article['title'],
                'ArticleNumber' => $article['artnum'],
                'Quantity' => oxRegistry::getConfig()->getRequestParameter($article['arthash']),
                'UnitPriceGross' => number_format($article['unitprice'] + ($article['unitprice'] / 100 * $article['vat']), '2', '.', ''),
                'TaxRate' => $article['vat'],
            );
            if (!empty($article['unique_article_number'])) {
                $item['UniqueArticleNumber'] = $article['unique_article_number'];
            }

            if ($article['title'] == 'Credit') {
                $item['Quantity'] = 1;
            }

            if (!empty($article['bruttoprice'])) {
                $item['UnitPriceGross'] = $article['bruttoprice'];
            }

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        return $shoppingBasket;
    }

    /**
     * check if the new api is used
     *
     * @return bool
     * @throws oxConnectionException
     */
    private function _isNewApi() {
        $api = oxDb::getDb()->getOne("SELECT RP_API FROM pi_ratepay_orders WHERE TRANSACTION_ID = '" . $this->_transactionId . "'");

        if (empty($api) || $api == null) {
            return false;
        }
        return true;
    }

    /**
     * get basket
     *
     * @return array
     */
    private function _getBasket()
    {
        $shoppingBasket = array();
        $util = new pi_ratepay_util_Utilities();
        $artnr = array();

        foreach ($this->_basket->getContents() AS $article) {

            $item = array(
                'Description' => $article->getTitle(),
                'ArticleNumber' => $article->getArticle()->oxarticles__oxartnum->value,
                'Quantity' => $article->getAmount(),
                'UnitPriceGross' => $article->getPrice()->getBruttoPrice() / $article->getAmount(),
                'TaxRate' => $article->getPrice()->getVat(),
                'UniqueArticleNumber' => $article->getArticle()->getId(),
            );

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        //wrapping costs
        if (method_exists($this->_basket, 'getWrappingCost') && $this->_basket->getWrappingCost()) {
            $wrappingCosts = $this->_basket->getWrappingCost()->getBruttoPrice();
            $wrappingVat = $this->_basket->getWrappingCost()->getVat();
        } elseif (method_exists($this->_basket, 'getFWrappingCosts') && $this->_basket->getFWrappingCosts()) {
            $wrappingCosts = $this->_basket->getFWrappingCosts();
            if ($this->_basket->getWrappCostNet() > 0) {
                $wrappingVat = $this->_basket->getWrappCostVatPercent();
            } else {
                $wrappingVat = 0;
            }
        } else {
            $wrappingCosts = 0;
        }
        if (!empty($wrappingCosts) && $wrappingCosts > 0) {
            $item = array(
                'Description' => 'Wrapping Costs',
                'ArticleNumber' => 'oxwrapping',
                'Quantity' => 1,
                'UnitPriceGross' => $util->getFormattedNumber($wrappingCosts, '2', '.'),
                'TaxRate' => $util->getFormattedNumber(ceil($wrappingVat), '2', '.'),
            );

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        //giftcard costs
        if (method_exists($this->_basket, 'getGiftCardCost') && $this->_basket->getGiftCardCost()) {
            $giftcardCosts = $this->_basket->getGiftCardCost()->getPrice();
            $giftcardVat = $this->_basket->getGiftCardCost()->getVat();
        } elseif (method_exists($this->_basket, 'getFGiftCardCosts') && $this->_basket->getFGiftCardCosts()) {
            $giftcardCosts = $this->_basket->getFGiftCardCosts();
            if ($this->_basket->getGiftCardCostNet() > 0) {
                $giftcardVat = $this->_basket->getGiftCardCostVatPercent();
            } else {
                $giftcardVat = 0;
            }
        } else {
            $giftcardCosts = 0;
        }
        if (!empty($giftcardCosts) && $giftcardCosts > 0) {
            $item = array(
                'Description' => 'Giftcard Costs',
                'ArticleNumber' => 'oxgiftcard',
                'Quantity' => 1,
                'UnitPriceGross' => $util->getFormattedNumber($giftcardCosts, '2', '.'),
                'TaxRate' => $util->getFormattedNumber(ceil($giftcardVat), '2', '.'),
            );

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        //payment costs
        if (method_exists($this->_basket, 'getPaymentCost') && $this->_basket->getPaymentCost()) {
            $paymentCosts = $this->_basket->getPaymentCost()->getPrice();
            $paymentVat = $this->_basket->getPaymentCost()->getVat();
        } elseif (method_exists($this->_basket, 'getPaymentCosts') && $this->_basket->getPaymentCosts()) {
            $paymentCosts = $this->_basket->getPaymentCosts();
            if ($this->_basket->getPayCostNet() > 0) {
                $paymentVat = $this->_basket->getPayCostVatPercent();
            } else {
                $paymentVat = 0;
            }
        } else {
            $paymentCosts = 0;
        }

        if (!empty($paymentCosts) && $paymentCosts > 0) {
            $item = array(
                'Description' => 'Giftcard Costs',
                'ArticleNumber' => 'oxgiftcard',
                'Quantity' => 1,
                'UnitPriceGross' => $util->getFormattedNumber($paymentCosts, '2', '.'),
                'TaxRate' => $util->getFormattedNumber(ceil($paymentVat), '2', '.'),
            );

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        //trusted protection
        if (method_exists($this->_basket, 'getTrustedShopProtectionCost') && $this->_basket->getTrustedShopProtectionCost()) {
            $tsProtectionCosts = $this->_basket->getTrustedShopProtectionCost()->getPrice();
            $tsProtectionVat = $this->_basket->getTrustedShopProtectionCost()->getVat();
        } elseif (method_exists($this->_basket, 'getTsProtectionCosts') && $this->_basket->getTsProtectionCosts()) {
            $tsProtectionCosts = $this->_basket->getTsProtectionCosts();
            if ($this->_basket->getTsProtectionNet() > 0) {
                $tsProtectionVat = $this->_basket->getTsProtectionVatPercent();
            } else {
                $tsProtectionNettoPrice = $tsProtectionCosts;
                $tsProtectionVat = 0;
            }
        } else {
            $tsProtectionCosts = 0;
        }

        if (!empty($tsProtectionCosts) && $tsProtectionCosts > 0) {
            $item = array(
                'Description' => 'Giftcard Costs',
                'ArticleNumber' => 'oxgiftcard',
                'Quantity' => 1,
                'UnitPriceGross' => $util->getFormattedNumber($tsProtectionCosts, '2', '.'),
                'TaxRate' => $util->getFormattedNumber(ceil($tsProtectionVat), '2', '.'),
            );

            $shoppingBasket['Items'][] = array('Item' => $item);
        }

        return $shoppingBasket;

    }

    /**
     * get country code
     *
     * @param $countryId
     * @return false|string
     */
    private function _getCountryCodeById($countryId)
    {
        return oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $countryId . "'");
    }

}