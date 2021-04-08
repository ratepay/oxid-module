<?php

/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */

require_once 'PiRatepayRateCalcBase.php';

/**
 * {@inheritdoc}
 *
 * Is also responsible for creating the RatePAY request and setting of the data.
 */
class PiRatepayRateCalc extends PiRatepayRateCalcBase
{
    /**
     * Optional parameters: RatePAY XML service and any implementation of
     * PiRatepayCalcDataInterface.
     * @param PiRatepayCalcDataInterface $piCalcData
     */
    public function __construct(PiRatepayCalcDataInterface $piCalcData = null)
    {
        /**
         * OXID-169
         * If the classes using backslash namespace format are not found
         * this might be due to using Windows system, handling filepath differently.
         * Then we call a specific class autoloader for those classes
         */
        if (!class_exists('RatePAY\RequestBuilder')) {
            require __DIR__ . '/../../autoloader.php';
            spl_autoload_register('ratepayAutoload');
        }

        if (isset($piCalcData)) {
            parent::__construct($piCalcData);
        } else {
            parent::__construct();
        }

        // OX-28 : add support for Rate 0% method
        $paymentMethod = $this->getPostParameter('smethod');
        if (!empty($paymentMethod)) {
            $this->setPaymentMethod($paymentMethod);
        }
    }

    /**
     * Get RatePAY rate details and set data. If not successful also set
     * error message and unset data.
     *
     * @see requestRateDetails()
     * @see setData()
     * @see setErrorMsg()
     * @param $subtype
     * @param string $sPaymentMethod
     * @return array $resultArray
     */
    public function getRatepayRateDetails($subtype, $sPaymentMethod)
    {
        try {
            $this->requestRateDetails($subtype);
            $this->setData(
                    $this->getDetailsTotalAmount(),
                    $this->getDetailsAmount(),
                    $this->getDetailsInterestRate(),
                    $this->getDetailsInterestAmount(),
                    $this->getDetailsServiceCharge(),
                    $this->getDetailsAnnualPercentageRate(),
                    $this->getDetailsMonthlyDebitInterest(),
                    $this->getDetailsNumberOfRates(),
                    $this->getDetailsRate(),
                    $this->getDetailsLastRate(),
                    $this->getDetailsPaymentFirstday(),
                    $this->getDetailsBankIban()
            );
        } catch (Exception $e) {
            $this->unsetData();
            $this->setErrorMsg($e->getMessage());
        }
        return $this->createFormattedResult();
    }

    /**
     * Create an assoc array of formated RatePAY rate details.
     *
     * @return array $resultArray
     */
    public function createFormattedResult()
    {
        if ($this->getLanguage() == 'DE' ||
            $this->getLanguage() == 'AT') {
            $currency = '&euro;';
            $decimalSeperator = ',';
            $thousandSepeartor = '.';
        } else {
            $currency = '';
            $decimalSeperator = '.';
            $thousandSepeartor = ',';
        }

        $resultArray = array();
        $resultArray['totalAmount'] = number_format((double) $this->getDetailsTotalAmount(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['amount'] = number_format((double) $this->getDetailsAmount(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['interestRate'] = number_format((double) $this->getDetailsInterestRate(), 2, $decimalSeperator, $thousandSepeartor);
        $resultArray['interestAmount'] = number_format((double) $this->getDetailsInterestAmount(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['serviceCharge'] = number_format((double) $this->getDetailsServiceCharge(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['annualPercentageRate'] = number_format((double) $this->getDetailsAnnualPercentageRate(), 2, $decimalSeperator, $thousandSepeartor);
        $resultArray['monthlyDebitInterest'] = number_format((double) $this->getDetailsMonthlyDebitInterest(), 2, $decimalSeperator, $thousandSepeartor);
        $resultArray['numberOfRatesFull'] = (int) $this->getDetailsNumberOfRates();
        $resultArray['numberOfRates'] = (int) $this->getDetailsNumberOfRates() - 1;
        $resultArray['rate'] = number_format((double) $this->getDetailsRate(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['lastRate'] = number_format((double) $this->getDetailsLastRate(), 2, $decimalSeperator, $thousandSepeartor).' '. $currency;
        $resultArray['paymentFirstdate'] = $this->getDetailsPaymentFirstday();
        $resultArray['bankIban'] = $this->getDetailsBankIban();

        return $resultArray;
    }

    /**
     * Returns the allowed month to calculate by time
     *
     * @return array month_allowed
     */
    public function getRatepayRateMonthAllowed()
    {
        $oSession = oxRegistry::getSession();
        $sShopId = $oSession->getVariable('shopId');
        $sRatePayUsrCountry =
            $oSession->getVariable('pi_ratepay_rate_usr_country');
        $settings = oxNew('pi_ratepay_Settings');
        $settings->loadByType(pi_ratepay_util_utilities::getPaymentMethod($this->paymentMethod), $sShopId, $sRatePayUsrCountry);
        $allowedRuntimes = array();
        $basketAmount = (float)$this->getRequestAmount();
        $rateMinNormal = $settings->pi_ratepay_settings__min_rate->rawValue;
        $runTimes = json_decode($settings->pi_ratepay_settings__month_allowed->rawValue);
        $interestRate = ((float)$settings->pi_ratepay_settings__interest_rate->rawValue / 12) / 100;
        // 0049008 : no need to calculate
        // $rateAmount will be equal to 0 if $interestRate is equal to 0
        // OX-60 : pre-request made on highest available runtime
        // to estimate the highest valid runtime for this basket
        if ($interestRate == 0) {
            try {
                $highestRuntime = $runTimes[count($runTimes)-1];
                $pi_calculator = new PiRatepayRateCalc();
                $pi_calculator->setRequestCalculationValue($highestRuntime);
                $pi_calculator->setRequestIban('');
                $pi_calculator->setRequestFirstday(28);
                $pi_resultArray = $pi_calculator->getRatepayRateDetails('calculation-by-time', $this->paymentMethod);
                $highestValidRunTime = $pi_resultArray['numberOfRatesFull'];

                $runTimes = array_filter($runTimes, function($runTime) use ($highestValidRunTime) {
                    return $runTime <= $highestValidRunTime;
                });

                return $runTimes;
            } catch (Exception $e) {
                return $runTimes;
            }
        }

        foreach ($runTimes AS $month) {
            $rateAmount = ceil($basketAmount * (($interestRate * pow((1 + $interestRate), $month)) / (pow((1 + $interestRate), $month) - 1)));

            if($rateAmount >= $rateMinNormal) {
                $allowedRuntimes[] = $month;
            }
        }

        return $allowedRuntimes;
    }

    /**
     * Creates, sends and validates the response of the rate details request.
     * Sets Data on success.
     * @param string $subtype
     * @throws Exception Throws exception on connection error or negative response.
     */
    private function requestRateDetails($subtype)
    {
        $modelFactory = new ModelFactory();
        $request_reason_msg = 'serveroff';
        $calculationData = array(
            'requestAmount'     => $this->getRequestAmount(),
            'interestRate'      => $this->getRequestInterestRate(),
            'requestSubtype'    => $subtype,
            'requestValue'      => $this->getRequestCalculationValue(),
            'paymentFirstday'   => $this->getRequestFirstday(),
            'bankAccount'       => $this->getRequestIban()
        );

        $shopId = oxRegistry::getSession()->getVariable('shopId');
        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType(pi_ratepay_util_utilities::getPaymentMethod($this->paymentMethod), $shopId);

        $modelFactory->setCalculationData($calculationData);
        if (!empty($this->getRequestOrderId())) {
            $modelFactory->setOrderId($this->getRequestOrderId());
            $modelFactory->setCustomerId($this->getRequestMerchantConsumerId());
        }
        $modelFactory->setShopId($shopId);
        $modelFactory->setPaymentType(strtolower($this->paymentMethod));
        $response = $modelFactory->doOperation('CALCULATION_REQUEST');

        if ($response->isSuccessful()) {
            $resultArray = $response->getResult();
            $this->setDetailsTotalAmount($response->getPaymentAmount());
            $this->setDetailsAmount($this->getRequestAmount());
            $this->setDetailsInterestRate($response->getInterestRate());
            $this->setDetailsInterestAmount($resultArray['interestAmount']);
            $this->setDetailsServiceCharge($resultArray['serviceCharge']);
            $this->setDetailsAnnualPercentageRate($resultArray['annualPercentageRate']);
            $this->setDetailsMonthlyDebitInterest($resultArray['monthlyDebitInterest']);
            $this->setDetailsNumberOfRates($response->getInstallmentNumber());
            $this->setDetailsRate($resultArray['rate']);
            $this->setDetailsLastRate($resultArray['lastRate']);
            $this->setDetailsPaymentFirstday($response->getPaymentFirstday());
            $this->setDetailsBankIban(oxRegistry::getSession()->getVariable('pi_ratepay_bank_iban'));
            $this->setMsg($response->getReasonMessage());
            $this->setCode($response->getReasonCode());
            $this->setErrorMsg('');
        } else {
            $this->setMsg('');
            $this->emptyDetails();
            throw new Exception($request_reason_msg);
        }
    }

    /**
     * Clear rate details with empty string
     */
    private function emptyDetails()
    {
        $this->setDetailsTotalAmount('');
        $this->setDetailsAmount('');
        $this->setDetailsInterestAmount('');
        $this->setDetailsServiceCharge('');
        $this->setDetailsAnnualPercentageRate('');
        $this->setDetailsMonthlyDebitInterest('');
        $this->setDetailsNumberOfRates('');
        $this->setDetailsRate('');
        $this->setDetailsLastRate('');
        $this->setDetailsPaymentFirstday('');
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->picalcdata->setPaymentMethod($paymentMethod);
    }
}
