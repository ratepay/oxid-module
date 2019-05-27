<?php


class pi_ratepay_module_config extends pi_ratepay_module_config_parent
{

    /**
     * Assignment helper for ratepay payment activity
     * @var array
     */
    protected $_aCountry2Payment2Configs = array(
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
            'invoice' => array(
                'active'=> 'blRPInvoiceActive',
                'sandbox' => 'blRPInvoiceSandbox',
                'profileid' => 'sRPInvoiceProfileId',
                'secret' => 'sRPInvoiceSecret',
            ),
            'installment' => array(
                'active' => 'blRPInstallmentActive',
                'sandbox' => 'blRPInstallmentSandbox',
                'profileid' => 'sRPInstallmentProfileId',
                'secret' => 'sRPInstallmentSecret',
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
            'invoice' => array(
                'active' => 'blRPAustriaInvoice',
                'sandbox' => 'blRPAustriaInvoiceSandbox',
                'profileid' => 'sRPAustriaInvoiceProfileId',
                'secret' => 'sRPAustriaInvoiceSecret',
            ),
            'installment' => array(
                'active' => 'blRPAustriaInstallment',
                'sandbox' => 'blRPAustriaInstallmentSandbox',
                'profileid' => 'sRPAustriaInstallmentProfileId',
                'secret' => 'sRPAustriaInstallmentSecret',
            ),
        ),
        'ch' => array(
            'rechnung' => array(
                'active' => 'blRPSwitzerlandInvoice',
                'sandbox' => 'blRPSwitzerlandInvoiceSandbox',
                'profileid' => 'sRPSwitzerlandInvoiceProfileId',
                'secret' => 'sRPSwitzerlandInvoiceSecret',
            ),
            'invoice' => array(
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
                'active' => 'blRPNetherlandElv',
                'sandbox' => 'blRPNetherlandElvSandbox',
                'profileid' => 'sRPNetherlandElvProfileId',
                'secret' => 'sRPNetherlandElvSecret',
            ),
            'invoice' => array(
                'active' => 'blRPNetherlandInvoice',
                'sandbox' => 'blRPNetherlandInvoiceSandbox',
                'profileid' => 'sRPNetherlandInvoiceProfileId',
                'secret' => 'sRPNetherlandInvoiceSecret',
            ),
        ),
    );

    /**
     * Returns url of country code
     *
     * @param $sCountryCode
     * @return string
     */
    public function piGetFlagUrl($sCountryCode)
    {
        $oConfig = $this->getConfig();
        $sShopUrl = $oConfig->getShopUrl();

        $sModuleAdminImgFlagsPath =
            "/modules/pi/pi_ratepay/out/admin/img/flags/";

        $sFlagUrl =
            $sShopUrl.
            $sModuleAdminImgFlagsPath.
            $sCountryCode.
            ".png";

        return $sFlagUrl;
    }

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
     * @param $sPaymentType
     * @return bool
     */
    public function piTestConnectionEstablished($sPaymentType, $sCountryCode)
    {
        $blValid = isset(
            $this->_aCountry2Payment2Configs[$sCountryCode][$sPaymentType]
        );
        if (!$blValid) return false;

        $aConfig =
            $this->_aCountry2Payment2Configs[$sCountryCode][$sPaymentType];

        $blConnected = (bool) $this->_piPerformProfileRequest($aConfig);

        return $blConnected;
    }

    /**
     * Overloading savig settings
     */
    public function saveConfVars()
    {
        parent::saveConfVars();
        $blIsRatePay = $this->piIsRatepayModuleConfig();
        if ($blIsRatePay) {
            $this->_piFetchAndSaveRatepayProfiles();
        }
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
            $aResult = $this->_piPerformProfileRequest($aConfigParams);

            if (!$aResult) {
                $blSandbox = $oConfig->getConfigParam($aConfigParams['sandbox']);
                $iEditLanguage = $oConfig->getRequestParameter("editlanguage");
                $oUtilsView = oxRegistry::get('oxUtilsView');
                $oLang = oxRegistry::get('oxLang');

                $sTranslationString = 'PI_RATEPAY_PROFILE_ERROR_CREDENTIALS_INVALID_';
                $sTranslationString .= ($blSandbox) ? 'INT' : 'LIVE';
                $sMessage = $oLang->translateString($sTranslationString, $iEditLanguage);

                return $oUtilsView->addErrorToDisplay($sMessage);
            }

            $oSettings = oxNew('pi_ratepay_Settings');
            $oSettings->piUpdateSettings($aActiveCombination, $aResult);
        }

        $this->addTplParam('blSaveSuccess', true);
    }

    /**
     * Performing profile request and returns result
     *
     * @param $aConfigParams
     * @return mixed
     */
    protected function _piPerformProfileRequest($aConfigParams)
    {
        $oConfig = $this->getConfig();

        $sSecurityCode = $oConfig->getConfigParam($aConfigParams['secret']);
        $sProfileId = $oConfig->getConfigParam($aConfigParams['profileid']);
        $blSandbox = $oConfig->getConfigParam($aConfigParams['sandbox']);
        $blActive = $oConfig->getConfigParam($aConfigParams['active']);

        $blValid = (
            $blActive &&
            !empty($sProfileId) &&
            !empty($sSecurityCode)
        );

        if (!$blValid) return false;

        $modelFactory = new ModelFactory();
        $modelFactory->setSecurityCode($sSecurityCode);
        $modelFactory->setProfileId($sProfileId);
        $modelFactory->setSandbox($blSandbox);
        $aResult = $modelFactory->doOperation('PROFILE_REQUEST');

        return $aResult;
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
                    isset($this->_aCountry2Payment2Configs[$sCountry][$sMethod]);
                if (!$blConfigExists) continue;

                $aConfig =
                    $this->_aCountry2Payment2Configs[$sCountry][$sMethod];
                $sActiveConfigParam = $aConfig['active'];
                $blIsActive =
                    $oConfig->getConfigParam($sActiveConfigParam);

                if (!$blIsActive) continue;

                $aActiveCombinations[] = array(
                    'country'       => $sCountry,
                    'method'          => $sMethod,
                    'configparams'  => $aConfig,
                    'requestmethod' => $sRequestMethod,
                );
            }
        }

        return $aActiveCombinations;
    }

}