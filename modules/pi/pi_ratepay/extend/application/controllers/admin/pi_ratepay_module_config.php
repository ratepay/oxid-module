<?php


class pi_ratepay_module_config extends pi_ratepay_module_config_parent
{

    /**
     * Assignment helper for ratepay payment activity
     * @var array
     */
    protected $_aCountry2Payment2Active = array(
        'de' => array(
            'rechnung' => array(
                'active'=> 'blRPInvoiceActive',
                'sandbox' => 'blRPInvoiceSandbox',
                'profileid' => 'sRPInvoiceProfileId',
                'secret' => 'sRPInvoiceSecret',
            ),
            'rate' => array(
                'active' => 'blRPInstallmentActive',
                'sandbox' => 'blRPInstallmentSandbox',
                'profileid' => 'sRPInstallmentProfileId',
                'secret' => 'sRPInstallmentSecret',
            ),
            'elv' => array(
                'active' => 'blRPElvActive',
                'sandbox' => 'blRPElvSandbox',
                'profileid' => 'sRPElvProfileId',
                'secret' => 'sRPElvSecret',
            ),
        ),
        'at' => array(
            'rechnung' => array(
                'active' => 'blRPAustriaInvoice',
                'sandbox' => 'blRPAustriaInvoiceSandbox',
                'profileid' => 'sRPAustriaInvoiceProfileId',
                'secret' => 'sRPAustriaInvoiceSecret',
            ),
            'rate' => array(
                'active' => 'blRPAustriaInstallment',
                'sandbox' => 'blRPAustriaInstallmentSandbox',
                'profileid' => 'sRPAustriaInstallmentProfileId',
                'secret' => 'sRPAustriaInstallmentSecret',
            ),
            'elv' => array(
                'active' => 'blRPAustriaElv',
                'sandbox' => 'blRPAustriaElvSandbox',
                'profileid' => 'sRPAustriaElvProfileId',
                'secret' => 'sRPAustriaElvSecret',
            ),
        ),
        'ch' => array(
            'rechnung' => array(
                'active' => 'blRPSwitzerlandInvoice',
                'sandbox' => 'blRPSwitzerlandInvoiceSandbox',
                'profileid' => 'sRPSwitzerlandInvoiceProfileId',
                'secret' => 'sRPSwitzerlandInvoiceSecret',
            ),
        ),
        'nl' => array(
            'rechnung' => array(
                'active' => 'blRPNetherlandInvoice',
                'sandbox' => 'blRPNetherlandInvoiceSandbox',
                'profileid' => 'sRPNetherlandInvoiceProfileId',
                'secret' => 'sRPNetherlandInvoiceSecret',
            ),
            'elv' => array(
                'active' => 'blRPAustriaElv',
                'sandbox' => 'blRPNetherlandElvSandbox',
                'profileid' => 'sRPNetherlandElvProfileId',
                'secret' => 'sRPNetherlandElvSecret',
            ),
        ),
    );

    /**
     * Method determines this is the config controller of
     * ratepay config page
     *
     * @param void
     * @return bool
     */
    public function piIsRatepayModuleConfig()
    {
        $blIsRatepayModuleConfig =
            ($this->_sModuleId == 'pi_ratepay');

        return $blIsRatepayModuleConfig;
    }

    /**
     * Returns if connection has been successfully established
     *
     * @param $sPaymentId
     * @return bool
     */
    public function piTestConnectionEstablished($sPaymentId)
    {
        return false;
    }


    public function saveConfVars()
    {
        parent::saveConfVars();
        $this->_piFetchAndSaveRatepayProfiles();
    }

    /**
     * Fetching available ratepay profiles and persist them into database
     *
     * @param void
     * @return void
     */
    protected function _piFetchAndSaveRatepayProfiles()
    {
        $oConfig = $this->getConfig();
        $aActiveCombinations = $this->_piGetActiveCombinations();
        foreach ($aActiveCombinations as $aActiveCombination) {
            $aConfigParams = $aActiveCombination['configparams'];

            $sSecurityCode = $oConfig->getConfigParam($aConfigParams['secret']);
            $sProfileId = $oConfig->getConfigParam($aConfigParams['profileid']);
            $blSandbox = $oConfig->getConfigParam($aConfigParams['sandbox']);

            $blValid = (
                !empty($sProfileId) &&
                !empty($sSecurityCode)
            );

            if (!$blValid) continue;

            $modelFactory = new ModelFactory();
            $modelFactory->setSecurityCode($sSecurityCode);
            $modelFactory->setProfileId($sProfileId);
            $modelFactory->setSandbox($blSandbox);
            $aResult = $modelFactory->doOperation('PROFILE_REQUEST');

            if (!$aResult) {
                $iEditLanguage = $oConfig->getRequestParameter("editlanguage");
                $oUtilsView = oxRegistry::get('oxUtilsView');
                $oLang = oxRegistry::get('oxLang');

                $sTranslationString = 'PI_RATEPAY_PROFILE_ERROR_CREDENTIALS_INVALID_';
                $sTranslationString .= ($blSandbox) ? 'INT' : 'LIVE';
                $sMessage = $oLang->translateString($sTranslationString, $iEditLanguage);

                return $oUtilsView->addErrorToDisplay($sMessage);
            }

            $this->_piUpdateSettings($aActiveCombination, $aResult);
        }
    }

    /**
     * Persist profile information into database
     *
     * @param $aActiveCombination
     * @param $aResult
     */
    protected function _piUpdateSettings($aActiveCombination, $aResult)
    {
        $oConfig = $this->getConfig();
        $sShopId = $oConfig->getShopId();
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
        $aMerchantConfig = $aResult['merchantConfig'];

        $oSettings = oxNew('pi_ratepay_settings');
        $oSettings->loadByType($sRequestMethod, $sShopId, $sCountry);

        $oSettings->pi_ratepay_settings__shopid = new oxField($sShopId);
        $oSettings->pi_ratepay_settings__active = new oxField($blActive);
        $oSettings->pi_ratepay_settings__profile_id = new oxField($sProfileId);
        $oSettings->pi_ratepay_settings__security_code = new oxField($sSecurityCode);
        $oSettings->pi_ratepay_settings__sandbox = new oxField($blSandbox);
        $oSettings->pi_ratepay_settings__url = new oxField($sUrl);
        $oSettings->pi_ratepay_settings__type = new oxField($sMethod);
        $oSettings->pi_ratepay_settings__limit_min =
            new oxField($aMerchantConfig['tx-limit-' . $sRequestMethod . '-min']);
        $oSettings->pi_ratepay_settings__limit_max =
            new oxField($aMerchantConfig['tx-limit-' . $sRequestMethod . '-max']);
        $oSettings->pi_ratepay_settings__limit_max_b2b =
            new oxField($aMerchantConfig['tx-limit-' . $sRequestMethod . '-max-b2b']);
        $oSettings->pi_ratepay_settings__b2b =
            new oxField($aMerchantConfig['b2b-' . $sRequestMethod]);
        $oSettings->pi_ratepay_settings__ala =
            new oxField($aMerchantConfig['delivery-address-' . $sRequestMethod]);
        $oSettings->pi_ratepay_settings__ala =
            new oxField($aMerchantConfig['delivery-address-' . $sRequestMethod]);

    }

    /**
     * Returns all active combinations of ratepay payments for certain countries
     *
     * @param void
     * @return array
     */
    protected function _piGetActiveCombinations()
    {
        $oConfig = $this->getConfig();
        $aCountries = pi_ratepay_util_utilities::$_RATEPAY_ALLOWED_COUNTRIES;
        $aMethods = pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD_NAMES;
        $aActiveCombinations = array();

        foreach ($aCountries as $sCountry) {
            foreach ($aMethods as $sRequestMethod => $sMethod) {
                $blConfigExists =
                    isset($this->_aCountry2Payment2Active[$sCountry][$sMethod]);
                if (!$blConfigExists) continue;

                $aConfig =
                    $this->_aCountry2Payment2Active[$sCountry][$sMethod];
                $sActiveConfigParam = $aConfig['active'];
                $blIsActive =
                    $oConfig->getConfigParam($sActiveConfigParam);

                if (!$blIsActive) continue;

                $aActiveCombinations[] = array(
                    'country'       => $sCountry,
                    'type'          => $sMethod,
                    'configparams'  => $aConfig,
                    'requestmethod' => $sRequestMethod,
                );
            }
        }

        return $aActiveCombinations;
    }

}