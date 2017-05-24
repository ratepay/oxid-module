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

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->_orderId = $orderId;
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
        $this->_sandbox = $sandbox;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->_paymentType = $paymentType;
    }

    public function doOperation($operation, $operationData = false)
    {
        switch ($operation) {
            case 'PAYMENT_INIT':
                return $this->_makePaymentInit();
                break;
            case 'PAYMENT_REQUEST':
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

        if (!empty($this->_orderId)) {
            $headArray['External'] = array('OrderId', $this->_orderId);
        }

        $modelBuilder = new RatePAY\ModelBuilder();
        $modelBuilder->setArray($headArray);

        return $modelBuilder;
    }

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
     * get country code
     *
     * @param $countryId
     * @return false|string
     */
    private function _getCountryCodeById($countryId) {
        return oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $countryId . "'");
    }

}