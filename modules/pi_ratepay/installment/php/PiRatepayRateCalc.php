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
     * Method name of RatePAY Installment
     * @var string
     */
    private $_paymentMethod = 'pi_ratepay_rate';

    /**
     * Optional parameters: RatePAY XML service and any implementation of
     * PiRatepayCalcDataInterface.
     * @param PiRatepayCalcDataInterface $piCalcData
     */
    public function __construct(PiRatepayCalcDataInterface $piCalcData = null)
    {

        if (isset($piCalcData)) {
            parent::__construct($piCalcData);
        } else {
            parent::__construct();
        }
    }

    /**
     * Get RatePAY rate details and set data. If not successful also set
     * error message and unset data.
     * @see requestRateDetails()
     * @see setData()
     * @see setErrorMsg()
     * @return array $resultArray
     */
    public function getRatepayRateDetails($subtype)
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
        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType('installment', oxRegistry::getSession()->getVariable('shopId'), oxRegistry::getSession()->getVariable('pi_ratepay_rate_usr_country'));
        $allowedRuntimes = array();

        $basketAmount = (float)$this->getRequestAmount();
        $rateMinNormal = $settings->pi_ratepay_settings__min_rate->rawValue;
        $runTimes = json_decode($settings->pi_ratepay_settings__month_allowed->rawValue);
        $interestRate = ((float)$settings->pi_ratepay_settings__interest_rate->rawValue / 12) / 100;

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
        $settings->loadByType($this->_paymentMethod, $shopId);

        $modelFactory->setCalculationData($calculationData);
        if (!empty($this->getRequestOrderId())) {
            $modelFactory->setOrderId($this->getRequestOrderId());
            $modelFactory->setCustomerId($this->getRequestMerchantConsumerId());
        }
        $modelFactory->setShopId($shopId);
        $modelFactory->setPaymentType(strtolower($this->_paymentMethod));
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
}
