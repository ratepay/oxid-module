<?php
    /**
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     *
     * @package pi_ratepay_rate_calculator
     * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
     */
    require_once 'PiRatepayRateCalc.php';
    require_once 'path.php';

    $pi_calculator = new PiRatepayRateCalc();
    $calcValue = $pi_calculator->getPostParameter('calcValue');
    $calcMethod = $pi_calculator->getPostParameter('calcMethod');
    $bankAccount = $pi_calculator->getPostParameter('bankAccount');
    $paymentFirstday = $pi_calculator->getPostParameter('paymentFirstday');

    if ($calcValue != '' && $calcMethod != '') {
        if ($calcMethod == "calculation-by-time" || $calcMethod == "calculation-by-rate") {
            if (empty($calcValue)) {
                $pi_calculator->setErrorMsg('novalue');
            } else if ($calcMethod == "calculation-by-time" && is_numeric($calcValue)) {
                if (preg_match('/^[0-9]{1,3}$/', $calcValue)) {
                    $pi_calculator->setRequestCalculationValue($calcValue);
                    $pi_calculator->setRequestIban($bankAccount);
                    $pi_calculator->setRequestFirstday($paymentFirstday);
                    $pi_resultArray = $pi_calculator->getRatepayRateDetails($calcMethod);
                } else {
                    $pi_calculator->setErrorMsg('wrongvalue');
                }
            } else if ($calcMethod == "calculation-by-rate") {
                $pi_value = trim($calcValue);
                if (preg_match('/^\d+(,)?\d{0,2}/', $pi_value)) {
                    $pi_value = str_replace(".", "", $pi_value);
                    $pi_value = (substr($pi_value, -1) != ",") ? str_replace(",", ".", $pi_value) : str_replace(",", "", $pi_value);
                    $pi_calculator->setRequestCalculationValue($pi_value);
                    $pi_calculator->setRequestIban($bankAccount);
                    $pi_calculator->setRequestFirstday($paymentFirstday);
                    $pi_resultArray = $pi_calculator->getRatepayRateDetails($calcMethod);
                } else if (preg_match('/^\d+(\.)?\d{0,2}/', $pi_value)) {
                    $pi_value = str_replace(",", "", $pi_value);
                    $pi_value = (substr($pi_value, -1) != ".") ? str_replace(",", ".", $pi_value) : str_replace(",", "", $pi_value);
                    $pi_calculator->setRequestCalculationValue($pi_value);
                    $pi_calculator->setRequestIban($bankAccount);
                    $pi_calculator->setRequestFirstday($paymentFirstday);
                    $pi_resultArray = $pi_calculator->getRatepayRateDetails($calcMethod);
                } else {
                    $pi_calculator->setErrorMsg('wrongvalue');
                }
            } else {
                $pi_calculator->setErrorMsg('wrongvalue');
            }
        } else {
            $pi_calculator->setErrorMsg('wrongsubtype');
        }
    } else {
        $pi_calculator->prepareDetailsData();
        $pi_resultArray = $pi_calculator->createFormattedResult();
    }

    $pi_language = $pi_calculator->getLanguage();
    $pi_amount = $pi_calculator->getRequestAmount();

    if ($pi_language == "DE" || $pi_language == "AT") {
        require 'languages/german.php';
        $pi_currency = 'EUR';
        $pi_decimalSeperator = ',';
        $pi_thousandSeperator = '.';
    } else {
        require 'languages/english.php';
        $pi_currency = 'EUR';
        $pi_decimalSeperator = '.';
        $pi_thousandSeperator = ',';
    }
    $pi_amount = number_format($pi_amount, 2, $pi_decimalSeperator, $pi_thousandSeperator);
    if ($pi_calculator->getErrorMsg() != '') {
        if ($pi_calculator->getErrorMsg() == 'serveroff') {
            echo "<div class='pirperror' id='pirperror'>" . $pi_lang_error . ":&nbsp;&nbsp;" . $pi_lang_server_off . "</div>";
        } else if ($pi_calculator->getErrorMsg() == 'wrongvalue') {
            echo "<div class='pirperror' id='pirperror'>" . $pi_lang_error . ":&nbsp;&nbsp;" . $pi_lang_wrong_value . "</div>";
        } else if ($pi_calculator->getErrorMsg() == 'novalue') {
                echo "<div class='pirperror' id='pirperror'>" . $pi_lang_error . ":&nbsp;&nbsp;" . $pi_lang_no_value . "</div>";
        } else {
            echo "<div class='pirperror' id='pirperror'>" . $pi_lang_error . ":&nbsp;&nbsp;" . $pi_lang_request_error_else . "</div>";
        }
    } else{
        if (!empty($pi_resultArray)) {
            $rp_reason_code_translation = 'rp_reason_code_translation_' . $pi_calculator->getCode();
?>
            <div class="rp-table-striped">
                <div>
                    <div class="text-center text-uppercase" colspan="2">
                        <?php echo $rp_personal_calculation; ?>
                    </div>
                </div>

                <div>
                    <div class="warning small text-center" colspan="2">
                        <?php echo $$rp_reason_code_translation; ?>
                        <br/>
                        <?php echo $rp_calulation_example; ?>
                    </div>
                </div>

                <div class="rp-menue">
                    <div colspan="2" class="small text-right">
                        <a class="rp-link" id="rp-show-installment-plan-details" onclick="changeDetails()">
                            Zeige Details
                            <img src="modules/pi_ratepay/installment/resources/icon-enlarge.png" class="rp-details-icon" />
                        </a>
                        <a class="rp-link" id="rp-hide-installment-plan-details" onclick="changeDetails()">
                            Schließe Details
                            <img src="modules/pi_ratepay/installment/resources/icon-shrink.png" class="rp-details-icon" />
                        </a>
                    </div>
                </div>

                <div id="rp-installment-plan-details">
                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('amount')" onmouseout="piMouseOut('amount')">
                            <?php echo $rp_cash_payment_price; ?>
                            <p id="amount" class="rp-installment-plan-description small">
                                <?php echo $rp_mouseover_cash_payment_price; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['amount']; ?>
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('serviceCharge')" onmouseout="piMouseOut('serviceCharge')">
                            <?php echo $rp_service_charge; ?>
                            <p id="serviceCharge" class="rp-installment-plan-description small">
                                <?php echo $rp_mouseover_service_charge; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['serviceCharge']; ?>
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('annualPercentageRate')" onmouseout="piMouseOut('annualPercentageRate')">
                            <?php echo $rp_effective_rate; ?>
                            <p id="annualPercentageRate" class="rp-installment-plan-description small"><?php echo $rp_mouseover_effective_rate; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['annualPercentageRate']; ?> %
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('interestRate')" onmouseout="piMouseOut('interestRate')">
                            <?php echo $rp_debit_rate; ?>
                            <p id="interestRate" class="rp-installment-plan-description small"><?php echo $rp_mouseover_debit_rate; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['interestRate']; ?> %
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('interestAmount')" onmouseout="piMouseOut('interestAmount')">
                            <?php echo $rp_interest_amount; ?>
                            <p id="interestAmount" class="rp-installment-plan-description small"><?php echo $rp_mouseover_interest_amount; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['interestAmount']; ?>
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div colspan="2"></div>
                    </div>


                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('rate')" onmouseout="piMouseOut('rate')">
                            <?php echo $pi_resultArray['numberOfRates']; ?> <?php echo $rp_duration_month; ?>
                            <p id="rate" class="rp-installment-plan-description small"><?php echo $rp_mouseover_duration_month; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['rate']; ?>
                        </div>
                    </div>

                    <div class="rp-installment-plan-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('lastRate')" onmouseout="piMouseOut('lastRate')">
                            <?php echo $rp_last_rate; ?>
                            <p id="lastRate" class="rp-installment-plan-description small"><?php echo $rp_mouseover_last_rate; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['lastRate']; ?>
                        </div>
                    </div>
                </div>

                <div id="rp-installment-plan-no-details">
                    <div class="rp-installment-plan-no-details">
                        <div class="rp-installment-plan-title" onmouseover="piMouseOver('rate2')" onmouseout="piMouseOut('rate2')">
                            <?php echo $pi_resultArray['numberOfRatesFull']; ?> <?php echo $rp_mouseover_duration_month; ?>
                            <p id="rate2" class="rp-installment-plan-description small"><?php echo $rp_mouseover_duration_month; ?></p>
                        </div>
                        <div class="text-right">
                            <?php echo $pi_resultArray['rate']; ?>
                        </div>
                    </div>
                </div>
                <div class="rp-installment-plan-details">
                    <div class="rp-installment-plan-title" onmouseover="piMouseOver('totalAmount')" onmouseout="piMouseOut('totalAmount')">
                        <?php echo $rp_total_amount; ?>
                        <p id="totalAmount" class="rp-installment-plan-description small"><?php echo $rp_mouseover_total_amount; ?></p>
                    </div>
                    <div class="text-right">
                        <?php echo $pi_resultArray['totalAmount']; ?>
                    </div>
                </div>
            </div>
            <br/>
<?php
        }
    }
