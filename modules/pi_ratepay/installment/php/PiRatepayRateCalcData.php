<?php
/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */
if (!function_exists('getShopBasePath')) {

    function getShopBasePath()
    {
        return dirname(__FILE__) . '/../../../../';
    }

}

/*if (!function_exists('isAdmin')) {

    function isAdmin()
    {
        return false;
    }

}*/

// custom functions file
require_once getShopBasePath() . 'modules/functions.php';

// Generic utility method file
require_once getShopBasePath() . 'core/oxfunctions.php';

require_once 'PiRatepayRateCalcDataInterface.php';
require_once getShopBasePath() . 'core/oxdb.php';

// get bootstrap since 4.7
require_once getShopBasePath() . 'bootstrap.php';


    /**
 * {@inheritdoc}
 *
 * Concrete implementation for OXID
 */
class PiRatepayRateCalcData implements PiRatepayRateCalcDataInterface
{
    /**
     * {@inheritdoc}
     * @return string
     */
    public function getProfileId()
    {

        $settings = $this->_getSettings();

        $profileId = $settings->pi_ratepay_settings__profile_id->rawValue;
        return $profileId;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getSecurityCode()
    {
        $settings = $this->_getSettings();

        $securityCode = $settings->pi_ratepay_settings__security_code->rawValue;
        return $securityCode;
    }

    /**
     * {@inheritdoc}
     * @return boolean
     */
    public function isLive()
    {
        $settings = $this->_getSettings();

        $sandbox = $settings->pi_ratepay_settings__sandbox->rawValue;
        if ($sandbox == 1) {
            $live = false;
        } else {
            $live = true;
        }
        return $live;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getSecurityCodeHashed()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return oxRegistry::getSession()->getVariable('pi_ratepay_rate_trans_id');
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionShortId()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getOrderId()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getMerchantConsumerId()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getMerchantConsumerClassification()
    {
        return '';
    }

    public function getBankOwner() {
        $owner = oxRegistry::getSession()->getVariable('bankOwner');

        return $owner;
    }

    /**
     * {@inheritdoc}
     * @return type
     */
    public function getAmount()
    {
        $basket = oxRegistry::getSession()->getVariable('basketAmount');

        return $basket;
    }

    /**
     * {@inheritdoc}
     *
     * Return DE for German Calculator. Everything else will be English.
     * @return string
     */
    public function getLanguage()
    {
        $oxLangInstance = oxRegistry::getLang();
        $languageAbbervation = strtoupper($oxLangInstance->getLanguageAbbr($oxLangInstance->getBaseLanguage()));
        if ($languageAbbervation == 'DEU' || $languageAbbervation == 'AUT')
            return 'DE';
        return $languageAbbervation;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getInterestRate()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     *
     * @param string $total_amount
     * @param string $amount
     * @param string $interest_amount
     * @param string $service_charge
     * @param string $annual_percentage_rate
     * @param string $monthly_debit_interest
     * @param string $number_of_rates
     * @param string $rate
     * @param string $last_rate
     */
    public function setData(
        $total_amount,
        $amount,
        $interest_rate,
        $interest_amount,
        $service_charge,
        $annual_percentage_rate,
        $monthly_debit_interest,
        $number_of_rates,
        $rate,
        $last_rate,
        $payment_firstday,
        $bank_iban
    )
    {
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_total_amount', $total_amount
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_amount', $amount
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_interest_rate', $interest_rate
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_interest_amount', $interest_amount
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_service_charge', $service_charge
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_annual_percentage_rate', $annual_percentage_rate
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_monthly_debit_interest', $monthly_debit_interest
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_number_of_rates', $number_of_rates
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_rate', $rate
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_last_rate', $last_rate
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_payment_firstday', $payment_firstday
        );
        oxRegistry::getSession()->setVariable(
            'pi_ratepay_rate_bank_iban', $bank_iban
        );
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getData()
    {
        $array = array(
            'total_amount'           => oxRegistry::getSession()->getVariable('pi_ratepay_rate_total_amount'),
            'amount'                 => oxRegistry::getSession()->getVariable('pi_ratepay_rate_amount'),
            'interest_rate'          => oxRegistry::getSession()->getVariable('pi_ratepay_rate_interest_rate'),
            'interest_amount'        => oxRegistry::getSession()->getVariable('pi_ratepay_rate_interest_amount'),
            'service_charge'         => oxRegistry::getSession()->getVariable('pi_ratepay_rate_service_charge'),
            'annual_percentage_rate' => oxRegistry::getSession()->getVariable('pi_ratepay_rate_annual_percentage_rate'),
            'monthly_debit_interest' => oxRegistry::getSession()->getVariable('pi_ratepay_rate_monthly_debit_interest'),
            'number_of_rates'        => oxRegistry::getSession()->getVariable('pi_ratepay_rate_number_of_rates'),
            'rate'                   => oxRegistry::getSession()->getVariable('pi_ratepay_rate_rate'),
            'last_rate'              => oxRegistry::getSession()->getVariable('pi_ratepay_rate_last_rate'),
            'payment_firstday'       => oxRegistry::getSession()->getVariable('pi_ratepay_rate_payment_firstday'),
            'bank_iban'              => oxRegistry::getSession()->getVariable('pi_ratepay_rate_bank_iban')
        );
        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData()
    {
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_total_amount');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_amount');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_interest_rate');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_interest_amount');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_service_charge');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_annual_percentage_rate');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_monthly_debit_interest');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_number_of_rates');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_rate');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_last_rate');
        oxRegistry::getSession()->deleteVariable('pi_ratepay_rate_payment_firstday');
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getPaymentFirstdayConfig()
    {
        $settings = $this->_getSettings();
        return $settings->pi_ratepay_settings__payment_firstday->rawValue;
    }

    /**
     * {@inheritdoc}
     * @param string $var
     * @return string
     */
    public function getGetParameter($var)
    {
        if (!is_null($_GET)) {
            return array_key_exists($var, $_GET)? $_GET[$var] : '';
        } else {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     * @param string $var
     * @return string
     */
    public function getPostParameter($var)
    {
        if (!is_null($_POST)) {
            return array_key_exists($var, $_POST)? $_POST[$var] : '';
        } else {
            return '';
        }
    }

    /**
     * Get installment settings
     * @return pi_ratepay_Settings
     */
    private function _getSettings()
    {

        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType(strtolower('installment'), oxRegistry::getSession()->getVariable('shopId'));


        return $settings;
    }

}
