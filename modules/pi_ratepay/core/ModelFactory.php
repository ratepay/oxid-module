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
     * do operation
     *
     * @param $operation
     * @param bool $operationData
     * @return bool|mixed|object
     */
    public function doOperation($operation, $operationData = false)
    {
        switch ($operation) {
            case 'PAYMENT_INIT':
                return $this->_makePaymentInit();
                break;
            case 'PAYMENT_REQUEST':
                return $this->_makePaymentRequest();
                break;
            case 'PAYMENT_QUERY':
                break;
            case 'CONFIRMATION_DELIVERY':
                break;
            case 'PAYMENT_CHANGE':
                break;
            case 'PROFILE_REQUEST':
                return $this->_makeProfileRequest();
                break;
        }
    }

    /**
     * return the head for an request
     */
    private function _getHead() {
        if ($this->_profileId && $this->_securityCode) {
            $profileId = $this->_profileId;
            $securityCode = $this->_securityCode;
        } else {
            $util = new pi_ratepay_util_Utilities();
            $paymentMethod =  $util->getPaymentMethod($this->_paymentType);

            $paymentMethod = strtolower($paymentMethod);
            $country = $this->_getCountryCodeById($this->getUser()->oxuser__oxcountryid->value);
            $settings = oxNew('pi_ratepay_settings');
            if ($country) {
                $settings->loadByType($paymentMethod, oxRegistry::getSession()->getVariable('shopId'), $country);
            } else {
                $settings->loadByType($paymentMethod, oxRegistry::getSession()->getVariable('shopId'));
            }
            $profileId = $settings->pi_ratepay_settings__profile_id->rawValue;
            $securityCode = $settings->pi_ratepay_settings__security_code->rawValue;
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
            $external['External']['OrderId'] = $this->_orderId;
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
                        'DirectDial' => $this->getUser()->oxuser__oxfon->value
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
            $settings->loadByType($util->getPaymentMethod('pi_ratepay_rate'), $this->getSession()->getVariable('shopId'));
            if ($this->getSession()->getVariable('pi_rp_rate_pay_method') === 'pi_ratepay_rate_radio_elv'
                && $settings->pi_ratepay_settings__activate_elv->rawValue == 1) {
                $contentArr['Customer']['BankAccount'] = $this->_getCustomerBankdata($this->_paymentType);
                $contentArr['Payment']['DebitPayType'] = 'DIRECT-DEBIT';
            }
        }

        $mbContent = new RatePAY\ModelBuilder('Content');
        $mbContent->setArray($contentArr);

        $rb = new \RatePAY\RequestBuilder($this->_sandbox);

        $paymentRequest = $rb->callPaymentRequest($head, $mbContent);
        return $paymentRequest;
    }

    /**
     * get installment data
     * @return array
     */
    private function _getInstallmentData() {
        $util = new pi_ratepay_util_Utilities();
        return array(
            'InstallmentNumber'     => $this->getSession()->getVariable('pi_ratepay_rate_number_of_rates'),
            'InstallmentAmount'     => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_rate'), '2', '.'),
            'LastInstallmentAmount' => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_last_rate'),'2', '.'),
            'InterestRate'          => $util->getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_interest_rate'), '2', '.'),
            'PaymentFirstday'       => $this->getSession()->getVariable('pi_ratepay_rate_payment_firstday'),
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
     * get basket
     *
     * @return array
     */
    private function _getBasket()
    {
        $shoppingBasket = array();
        foreach ($this->_basket->getContents() AS $article) {

            $item = array(
                'Description' => $article->getTitle(),
                'ArticleNumber' => $article->getArticle()->oxarticles__oxartnum->value,
                'Quantity' => $article->getAmount(),
                'UnitPriceGross' => $article->getPrice()->getBruttoPrice(),
                'TaxRate' => $article->getPrice()->getVat(),
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
    private function _getCountryCodeById($countryId) {
        return oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $countryId . "'");
    }

}