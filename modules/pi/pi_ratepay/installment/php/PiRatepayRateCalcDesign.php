<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
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
    $pi_companyName = $pi_calculator->getRequestCompanyName();

    $sPaymentMethod = $pi_calculator->getPaymentMethod();

    $oSettings = $pi_calculator->getSettings();
    $sSettlementType = $oSettings->getSettlementType(); // debit, banktransfer, both

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
    <?php if ($sPaymentMethod != 'pi_ratepay_rate0') { ?>
    <div class="row"<?php if ($sPaymentMethod == 'pi_ratepay_rate0') { echo 'style="display: none"';  }?>>
        <div class="col-md-10">
            <?php
                echo $rp_calculation_intro_part1;
                echo $rp_calculation_intro_part2;
                echo $rp_calculation_intro_part3;
            ?>
        </div>
    </div>
    <?php } ?>
    <div class="row"<?php if ($sPaymentMethod == 'pi_ratepay_rate0') { echo 'style="display: none"';  }?>>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center" id="<?php echo $sPaymentMethod; ?>_firstInput">
                    <h2><?php echo $rp_runtime_title; ?></h2>
                    <?php echo $rp_runtime_description; ?>
                </div>
                <input type="hidden" id="<?php echo $sPaymentMethod; ?>_rate_elv" name="rate_elv" value="<?php echo $pi_rate_elv ?>">
                <input type="hidden" id="<?php echo $sPaymentMethod; ?>_rate" name="rate" value="<?php echo $pi_rate ?>">
                <input type="hidden" id="<?php echo $sPaymentMethod; ?>_paymentFirstday" name="paymentFirstday" value="<?php echo $pi_firstday ?>">
                <input type="hidden" id="<?php echo $sPaymentMethod; ?>_month" name="month" value="">
                <input type="hidden" id="<?php echo $sPaymentMethod; ?>_mode" name="mode" value="">
                <div class="panel-body">
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <?php foreach ($pi_monthAllowed AS $month) { ?>
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-default rp-btn-runtime" type="button" onclick="piRatepayRateCalculatorAction('runtime', '<?php echo $sPaymentMethod; ?>', <?php echo $month; ?>);" id="<?php echo $sPaymentMethod; ?>_piRpInput-buttonMonth-<?php echo $month; ?>" role="group"><?php echo $month; ?></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading text-center" id="<?php echo $sPaymentMethod; ?>_secondInput">
                    <h2><?php echo $rp_rate_title; ?></h2>
                    <?php echo $rp_rate_description; ?>
                </div>

                <div class="panel-body">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">&euro;</span>
                        <input type="text" id="<?php echo $sPaymentMethod; ?>_rp-rate-value" class="form-control" aria-label="Amount" />
                        <span class="input-group-btn">
                            <button class="btn btn-default rp-btn-rate" onclick="piRatepayRateCalculatorAction('rate', '<?php echo $sPaymentMethod; ?>');" type="button" id="<?php echo $sPaymentMethod; ?>_piRpInput-buttonRuntime"><?php echo $rp_calculate_rate; ?></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-11" id="<?php echo $sPaymentMethod; ?>_piRpResultContainer"></div>
    </div>
    <?php if (in_array($sSettlementType, array('both', 'debit'))): ?>
        <div id="<?php echo $sPaymentMethod; ?>_rp-rate-elv">
            <?php if ($sSettlementType == 'both'): ?>
                <strong class="rp-installment-header"><?php echo $rp_header_debit; ?></strong>
                <div class="row rp-payment-type-switch" id="<?php echo $sPaymentMethod; ?>_rp-switch-payment-type-bank-transfer" onclick="rp_change_payment(28, '<?php echo $sPaymentMethod; ?>')">
                    <a class="rp-link"><?php echo $rp_switch_payment_type_bank_transfer; ?></a>
                </div><br>
            <?php endif; ?>

            <div class="row rp-sepa-form rp-special-item rp-row-space">
                <?php echo $rp_mand_cred_notification; ?>
            </div>
            <br/>
            <div class="row rp-sepa-form rp-special-item rp-row-space">
                <?php echo $wcd_sepa_notice_block; ?>
            </div>
            <br/>
            <div class="row rp-sepa-form">
                <form>
                    <input type="hidden" name="rp-payment-type" id="<?php echo $sPaymentMethod; ?>_rp-payment-type" />
                    <div class="form-group">
                        <label class=""><?php echo $rp_account_holder; ?></label>
                        <?php if (!empty($pi_companyName)) { ?>
                        <select name="rp_sepa_use_company_name">
                            <option selected="selected" value="1"><?php echo $pi_companyName; ?></option>
                            <option value="0"><?php echo $pi_owner; ?></option>
                        </select>
                        <?php } else { ?>
                        <input type="text" class="form-control disabled" value="<?php echo $pi_owner; ?>" disabled />
                        <input type="hidden" name="rp_sepa_use_company_name" value="0" />
                        <?php } ?>
                    </div>
                    <!-- Account number is only allowed for customers with german billing address. IBAN must be used for all others -->
                    <div class="form-group">
                        <label class=""><?php echo $rp_iban; ?></label>
                        <input type="text" class="form-control required"  maxlength='50' size='37' id="<?php echo $sPaymentMethod; ?>_pi_ratepay_rate_bank_iban" onchange="updateCalculator('<?php echo $sPaymentMethod; ?>')" name="rp-iban-account-number" style="display: block"/>
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
            <div class="row rp-row-space rp-sepa-form" id="<?php echo $sPaymentMethod; ?>_rp-sepa-agreement">
                <input type="checkbox" name="rp-sepa-aggreement" id="<?php echo $sPaymentMethod; ?>_rp-sepa-aggreement" onchange="updateCalculator('<?php echo $sPaymentMethod; ?>')" class="required" />
                <?php echo $rp_sepa_terms_block_21; ?>
                <br><br>
                <?php echo $wcd_sepa_terms_please_note . $rp_sepa_terms_block_22; ?>
                <br/><br/>
                <?php echo $rp_sepa_terms_block_23; ?>
            </div><br/>
        </div>
        <?php if ($sSettlementType == 'both'): ?>
            <!-- Switching between payment type direct debit and bank transfer (which requires no sepa form) is only allowed if  -->
            <div id="<?php echo $sPaymentMethod; ?>_rp-switch-payment-type-direct-debit">
                <strong class="rp-installment-header"><?php echo $rp_header_bank_transfer; ?></strong>
                <div class="row rp-payment-type-switch" onclick="rp_change_payment(2, '<?php echo $sPaymentMethod; ?>')">
                    <a class="rp-link"><?php echo $rp_switch_payment_type_direct_debit; ?></a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php } ?>
