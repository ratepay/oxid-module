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
 * Model class for pi_ratepay_settings table
 * @extends oxBase
 */
class pi_ratepay_Settings extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'pi_ratepay_Settings';

    /**
     * Current country
     *
     * @var string
     */
    protected $_country = null;

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('pi_ratepay_settings');
    }

    /**
     * CE Shop uses 'oxbaseshop' as default shopId
     *
     * set shopId to '1' if shopId is 'oxbaseshop'
     * @return int
     */
    public function setShopIdToOne($shopId)
    {
        if($shopId == 'oxbaseshop'){
            $shopId = 1;
        }

        return $shopId;
    }

    /**
     * Load either invoice or installment settings
     *
     * @param string $type 'invoice' | 'installment'
     * @return boolean
     */
    public function loadByType($type, $shopId, $country = null)
    {
        if ($country !== null) {
            $this->_setCountry($country);
        }

        //getting at least one field before lazy loading the object
        $this->_addField('oxid', 0);
        $whereClause = array(
            $this->getViewName() . ".shopid" => $shopId,
            $this->getViewName() . ".type" => strtolower($type),
            $this->getViewName() . ".country" => $this->getCountry()
        );
        $selectQuery = $this->buildSelectString($whereClause);

        $this->_isLoaded = $this->assignRecord($selectQuery);

        return $this->_isLoaded;
    }

    /**
     * Persist profile information into database
     *
     * @param $aActiveCombination
     * @param $aResult
     * @return void
     */
    public function piUpdateSettings($aActiveCombination, $aResult)
    {
        $oConfig = $this->getConfig();
        $sShopId = $oConfig->getShopId();
        if ($sShopId == 'oxbaseshop') {
            $sShopId = 1;
        }
        $sCountry = $aActiveCombination['country'];
        $sRequestMethod = $aActiveCombination['requestmethod'];
        $sMethod = $aActiveCombination['method'];
        $aConfigParams = $aActiveCombination['configparams'];
        $blActive = $oConfig->getConfigParam($aConfigParams['active']);
        $sProfileId = $oConfig->getConfigParam($aConfigParams['profileid']);
        $sSecurityCode = $oConfig->getConfigParam($aConfigParams['secret']);
        $blSandbox = $oConfig->getConfigParam($aConfigParams['sandbox']);
        $sUrl = ($sCountry == 'nl') ?
            pi_ratepay_util_Utilities::$_RATEPAY_PRIVACY_NOTICE_URL_NL :
            pi_ratepay_util_Utilities::$_RATEPAY_PRIVACY_NOTICE_URL_DACH;

        $this->loadByType($sRequestMethod, $sShopId, $sCountry);

        $this->pi_ratepay_settings__shopid = new oxField($sShopId);
        $this->pi_ratepay_settings__active = new oxField($blActive);
        $this->pi_ratepay_settings__country = new oxField(strtoupper($sCountry));
        $this->pi_ratepay_settings__profile_id = new oxField($sProfileId);
        $this->pi_ratepay_settings__security_code = new oxField($sSecurityCode);
        $this->pi_ratepay_settings__sandbox = new oxField($blSandbox);
        $this->pi_ratepay_settings__url = new oxField($sUrl);
        $this->pi_ratepay_settings__type = new oxField($sRequestMethod);

        $aMerchantConfig = $aResult['merchantConfig'];
        $this->_piUpdateMerchantConfig($aMerchantConfig, $sRequestMethod);

        $blAddInstallmentData = ($sMethod == 'rate' && $blActive);
        if ($blAddInstallmentData) {
            $aInstallmentConfig = $aResult['installmentConfig'];
            $this->_piUpdateInstallmentConfig($aInstallmentConfig);
        }

        $this->_piUpdateElv($sMethod, $sCountry);

        $this->save();
    }

    /**
     * Update data related to elv payment
     *
     * @param $sMethod
     * @param $sCountry
     * @return void
     */
    protected function _piUpdateElv($sMethod, $sCountry)
    {
        $oConfig = $this->getConfig();
        $iIbanOnly = 1;

        $blElvDE = ($sMethod == 'elv' && $sCountry == 'de');
        $blElv = ($sMethod == 'elv');

        if ($blElvDE) {
            $sElvRequestParam = 'rp_iban_only_' . $sMethod . '_' . $sCountry;
            $sIbanOnly = $oConfig->getRequestParameter($sElvRequestParam);
            $iIbanOnly = (int) ($sIbanOnly);
        }

        if ($blElv) {
            $this->pi_ratepay_settings__iban_only = new oxField($iIbanOnly);
        }
    }

    /**
     * Check if checkbox has been set to on for given parameter.
     *
     * @param string $parameter
     * @return int 0 for false and 1 for true
     */
    protected function _isParameterCheckedYes($parameter)
    {
        $checked = 0;
        if ($parameter != null && $parameter == 'yes') {
            $checked = 1;
        }
        return $checked;
    }

    /**
     * Adding merchant config to settings
     *
     * @param $aMerchantConfig
     * @param $sRequestMethod
     * @return void
     */
    protected function _piUpdateMerchantConfig($aMerchantConfig, $sRequestMethod)
    {
        $this->pi_ratepay_settings__limit_min = new oxField($aMerchantConfig['tx-limit-'.$sRequestMethod.'-min']);
        $this->pi_ratepay_settings__limit_max = new oxField($aMerchantConfig['tx-limit-'.$sRequestMethod.'-max']);
        $this->pi_ratepay_settings__limit_max_b2b = new oxField($aMerchantConfig['tx-limit-'.$sRequestMethod.'-max-b2b']);
        $this->pi_ratepay_settings__b2b = new oxField($this->_isParameterCheckedYes($aMerchantConfig['b2b-'.$sRequestMethod]));
        $this->pi_ratepay_settings__ala = new oxField($this->_isParameterCheckedYes($aMerchantConfig['delivery-address-'.$sRequestMethod]));
        $this->pi_ratepay_settings__dfp = new oxField($this->_isParameterCheckedYes($aMerchantConfig['eligibility-device-fingerprint']));
        $this->pi_ratepay_settings__currencies = new oxField($aMerchantConfig['currency']);
        $this->pi_ratepay_settings__delivery_countries = new oxField($aMerchantConfig['country-code-delivery']);

        if ($this->pi_ratepay_settings__b2b->value !== 0) {
            $this->pi_ratepay_settings__b2b = new oxField($aMerchantConfig['tx-limit-'.$sRequestMethod.'-max']);
        }
    }

    /**
     * Adding installment configuration
     *
     * @param $aInstallmentConfig
     * @return void
     */
    protected function _piUpdateInstallmentConfig($aInstallmentConfig)
    {
        $this->pi_ratepay_settings__month_allowed = new oxField("[" .$aInstallmentConfig['month-allowed']."]");
        $this->pi_ratepay_settings__min_rate = new oxField($aInstallmentConfig['rate-min-normal']);
        $this->pi_ratepay_settings__interest_rate = new oxField($aInstallmentConfig['interestrate-default']);
        $this->pi_ratepay_settings__payment_firstday = new oxField($aInstallmentConfig['valid-payment-firstdays']);
    }


    public function getCountry()
    {
        if ($this->_country === null) {
            $this->_country = pi_ratepay_util_utilities::getCountry($this->getUser()->oxuser__oxcountryid->value);
        }
        return $this->_country;
    }

    private function _setCountry($country)
    {
        $this->_country = $country;
    }

    /**
     * Determines which settlement types are available in the connected RatePAY profile
     *
     * @return array
     */
    public function getAvailableSettlementTypes()
    {
        if (empty($this->getId())) {
            return array('debit', 'banktransfer', 'both'); // Settings not set yet
        }

        if ($this->pi_ratepay_settings__payment_firstday->value == '2,28') {
            return array('debit', 'banktransfer', 'both');
        } elseif ($this->pi_ratepay_settings__payment_firstday->value == '28') {
            return array('banktransfer');
        }
        return array('debit');
    }

    public function getSettlementType()
    {
        if ($this->pi_ratepay_settings__type->value != 'installment' || !in_array($this->pi_ratepay_settings__country->value, array('DE', 'AT'))) {
            return false;
        }

        $sConfigParam = ModelFactory::getSettlementTypeConfigParamByCountry($this->pi_ratepay_settings__country->value);

        $sSettlementType = $this->getConfig()->getConfigParam($sConfigParam);

        $aAvailableSettlementTypes = $this->getAvailableSettlementTypes();

        if (in_array($sSettlementType, $aAvailableSettlementTypes)) {
            return $sSettlementType;
        }
        return $aAvailableSettlementTypes[0];
    }
}
