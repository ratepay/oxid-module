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
     * Returns an array with test results of established
     */
    public function piGetConfigTestResults()
    {
        $aActiveCombinations = $this->_piGetActiveCombinations();

        foreach ($aActiveCombinations as $aActiveCombination) {

        }
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

        $blValid = (
            !empty($sProfileId) &&
            !empty($sSecurityCode)
        );

        if (!$blValid) return;

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