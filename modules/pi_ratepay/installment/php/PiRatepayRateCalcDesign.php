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


    $pi_calculator->unsetData();
    $pi_monthAllowed = $pi_calculator->getRatepayRateMonthAllowed();
    $pi_amount = $pi_calculator->getRequestAmount();
    $pi_language = $pi_calculator->getLanguage();
    $pi_firstday = $pi_calculator->getRequestFirstday();
    $pi_owner = $pi_calculator->getRequestBankOwner();
    $pi_valid_firstday = $pi_calculator->getValidRequestPaymentFirstday();

    if ($pi_language == "DE" || $pi_language == "AT") {
        require_once 'languages/german.php';
         $pi_currency = 'EUR';
         $pi_decimalSeperator = ',';
         $pi_thousandSeperator = '.';
    } else {
        require_once 'languages/english.php';
         $pi_currency = 'EUR';
         $pi_decimalSeperator = '.';
         $pi_thousandSeperator = ',';
    }

    $pi_amount = number_format($pi_amount, 2, $pi_decimalSeperator, $pi_thousandSeperator);

    if ($pi_calculator->getErrorMsg() != '') {
        if ($pi_calculator->getErrorMsg() == 'serveroff') {
            echo "<div>" . $pi_lang_server_off . "</div>";
        } else {
            echo "<div>" . $pi_lang_config_error_else . "</div>";
        }
    } else {
?>
<div class="rpContainer">
    <div class="row">
        <div class="col-md-10">
<?php
            echo $rp_calculation_intro_part1;
            echo $rp_calculation_intro_part2;
            echo $rp_calculation_intro_part3;
?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center" id="firstInput">
                    <h2><?php echo $rp_runtime_title; ?></h2>
                    <?php echo $rp_runtime_description; ?>
                </div>
                <input type="hidden" id="rate_elv" name="rate_elv" value="<?php echo $pi_rate_elv ?>">
                <input type="hidden" id="rate" name="rate" value="<?php echo $pi_rate ?>">
                <input type="hidden" id="month" name="month" value="">
                <input type="hidden" id="mode" name="mode" value="">
                <div class="panel-body">
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
<?php
                        foreach ($pi_monthAllowed AS $month) {
?>
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-default rp-btn-runtime" type="button" onclick="piRatepayRateCalculatorAction('runtime', <?php echo $month; ?>);" id="piRpInput-buttonMonth-<?php echo $month; ?>" role="group"><?php echo $month; ?></button>
                        </div>
<?php
                        }
?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center" id="secondInput">
                    <h2><?php echo $rp_rate_title; ?></h2>
                    <?php echo $rp_rate_description; ?>
                </div>

                <div class="panel-body">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">&euro;</span>
                        <input type="text" id="rp-rate-value" class="form-control" aria-label="Amount" />
                        <span class="input-group-btn">
                            <button class="btn btn-default rp-btn-rate" onclick="piRatepayRateCalculatorAction('rate');" type="button" id="piRpInput-buttonRuntime"><?php echo $rp_calculate_rate; ?></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-11" id="piRpResultContainer"></div>
    </div>
<?php
        if ($pi_firstday == 2) {
?>
        <div id="rp-rate-elv">
        <div class="row rp-sepa-form">
            <hr/>
        </div>

        <div class="row rp-row-space rp-sepa-form">
            <table class="rp-sepa-table small">
                <tr>
                    <td colspan="2">
                     <?php echo $wcd_address; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                     <?php echo $rp_creditor; ?>
                    </td>
                    <td>
                     <?php echo $wcd_creditor_id; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                     <?php echo $rp_mandate; ?>&nbsp;
                    </td>
                    <td>
                     <?php echo $rp_mandate_ref; ?>
                    </td>
                </tr>
            </table>
        </div>
        <br/>
        <div class="row rp-sepa-form small rp-special-item rp-row-space">
         <?php echo $wcd_sepa_notice_block; ?>
        </div>
        <br/>
        <div class="row rp-sepa-form">
            <form>
                <input type="hidden" name="rp-payment-type" id="rp-payment-type" />
                <input type="hidden" name="paymentFirstday" id="paymentFirstday" value="<?php echo $pi_firstday; ?>" />
                <div class="form-group">
                    <label class=""><?php echo $rp_account_holder; ?></label>
                    <input type="text" class="form-control disabled" name="rp-iban-account-owner" value="<?php echo $pi_owner; ?>" disabled /><!-- Show account holder name = billing address firstname and lastname -->
                </div>
                <!-- Account number is only allowed for customers with german billing address. IBAN must be used for all others -->
                <div class="form-group">
                    <label class=""><?php echo $rp_iban; ?></label>
                    <input type="text" class="form-control required" id="rp-iban-account-number" onchange="updateCalculator()" name="rp-iban-account-number" style="display: block"/>
                </div>
                <!-- Bank code is only necesarry if account number (no iban) is set -->
                <!--<div class="form-group" id="rp-form-bank-code">
                    <label class="small">$rp_bank_code; ?></label>
                    <input type="text" class="form-control" id="rp-bank-code" name="rp-bank-code" />
                </div>-->
            </form>
        </div>

        <!--<div class="row rp-row-space small rp-sepa-form" id="rp-show-sepa-agreement">
            <a class="rp-link"><?php echo $rp_sepa_link; ?></a>
        </div>-->
        <div class="row rp-row-space small rp-sepa-form" id="rp-sepa-agreement">
            <input type="checkbox" name="rp-sepa-aggreement" id="rp-sepa-aggreement" onchange="updateCalculator()" class="required" />
         <?php echo $wcd_sepa_terms_block_1; ?>
            <br><br>
         <?php echo $wcd_sepa_terms_please_note . $wcd_sepa_terms_block_2; ?>
            <br/><br/>
         <?php echo $wcd_sepa_terms_block_3; ?>
        </div><br/>
<?php
           if ($pi_firstday !== $pi_valid_firstday) {
               if ($pi_firstday == 2) {
?>
                   <div class="row small rp-payment-type-switch" id="rp-switch-payment-type-bank-transfer" onclick="rp_change_payment(28)">
                       <a class="rp-link"><?php echo $rp_switch_payment_type_bank_transfer; ?></a>
                   </div>
               </div>
               <!-- Switching between payment type direct debit and bank transfer (which requires no sepa form) is only allowed if  -->
               <div class="row small rp-payment-type-switch" id="rp-switch-payment-type-direct-debit" onclick="rp_change_payment(2)">
                   <a class="rp-link"><?php echo $rp_switch_payment_type_direct_debit; ?></a>
               </div>
<?php
               }
            }
        }
?>

</div>
<?php
    }
?>
