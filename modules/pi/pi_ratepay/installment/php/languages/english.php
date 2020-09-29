<?php

/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */
$rp_runtime_title = "Duration";
$rp_runtime_description = "Number of monthly installments";
$rp_rate_title = "Installment amount";
$rp_rate_description = "Amount of the monthly installments";
$rp_calculation_intro_part1 = "In the following you can decide how you want to pay the installments. ";
$rp_calculation_intro_part2 = "Conveniently determine the number of installments and thus <b> the duration </b> of the installment payment. ";
$rp_calculation_intro_part3 = "Or simply determine the desired <b>monthly installment amount.</b>";
$rp_calculate_rate = "Calculate rate";
$rp_total_amount = "Total amount";
$rp_cash_payment_price = "Value of goods";
$rp_calulation_example = "The rate calculation can differ from the rate plan";
$rp_interest_amount = "Interest amount";
$rp_service_charge = "Service charge";
$rp_effective_rate = "Effective rate";
$rp_debit_rate = "Interest rate per month";
$rp_duration_time = "Duration";
$rp_duration_month = " monthly installments &agrave;";
$rp_last_rate = "plus a last installment &agrave;";
$rp_months = "Months";
$rp_error_message = "An error has occurred. Please contact the shop operator.";
$rp_mouseover_cash_payment_price = "Sum of all items in your shopping cart, including shipping costs etc.";
$rp_mouseover_service_charge = "One-time processing fee for installments per order.";
$rp_mouseover_effective_rate = "Total cost of the loan as an annual percentage.";
$rp_mouseover_debit_rate = "Periodic percentage, applied to the loan drawn.";
$rp_mouseover_interest_amount = "Concrete interests amount";
$rp_mouseover_total_amount = "Sum of the amounts to be paid by the buyer from the value of the goods, contract conclusion fee and interest.";
$rp_mouseover_duration_time = "Duration of the installment plan (can be shortened by special repayments).";
$rp_mouseover_duration_month = "Partial amount due monthly";
$rp_mouseover_last_rate = "Partial amount due in the last month";
$rp_calculator = "Installment calculator";
$rp_personal_calculation = "Personal rate calculation";
$rp_reason_code_translation_603 = "The desired installment corresponds to the given conditions.";
$rp_reason_code_translation_671 = "The last installment was lower than allowed. Duration and/or installment amount have been adjusted.";
$rp_reason_code_translation_688 = "The installment was lower than allowed for long-term installment plans. The duration has been adjusted.";
$rp_reason_code_translation_689 = "The installment was lower than allowed for short term installment plans. The duration has been adjusted.";
$rp_reason_code_translation_695 = "The installment is too high for the minimum available duration. The installment amount has been reduced.";
$rp_reason_code_translation_696 = "The requestes installment amount is too low. It has been increased.";
$rp_reason_code_translation_697 = "No corresponding duration is available for the selected installment amount. The installment amount has been adjusted.";
$rp_reason_code_translation_698 = "The installment was too low for the maximum available duration. The installment amount has been increased.";
$rp_reason_code_translation_699 = "The installment is too high for the minimum available duration. The installment amount has been reduced.";

$rp_header_bank_transfer = "Installment by bank transfer";
$rp_header_debit = "Installment by debit";
$rp_switch_payment_type_bank_transfer = "I would like to make the installment payments myself and not pay by direct debit";
$rp_switch_payment_type_direct_debit = "I would like to conveniently pay the installments by direct debit";
$rp_address = "Ratepay GmbH, Franklinstra&szlig;e 28-29, 10587 Berlin";
$wcd_address = "Wirecard Bank AG, Einsteinring 35, 85609 Aschheim";
$rp_creditor = "Creditor ID";
$rp_creditor_id = "DE39RPY00000568463";
$wcd_creditor_id = "DE49ZZZ00000002773";
$rp_mandate = "Mandate reference";
$rp_mandate_ref = "(will be sent after the purchase is completed)";
$rp_insert_bank_data = "Please enter your bank details";
$rp_sepa_account_information = "IBAN Account information";
$rp_classic_account_information = "Classic Account information";
$rp_account_holder = "Account owner";
$rp_iban = "IBAN"; // "IBAN oder klassische Kontonummer"
$rp_account_number = "Account number";
$rp_bank_code = "BIC";
$rp_sepa_link = "Read the declaration of consent to the SEPA mandate";
$rp_sepa_terms_block_1 = "I hereby consent to the forwarding of my data ";
$rp_sepa_terms_block_2 = "according to ";
$rp_sepa_terms_block_3 = "and authorize them to collect payments related to this purchase contract from my aforementioned account by direct debit. At the same time, I instruct my bank to redeem the direct debits drawn by Ratepay GmbH into my account.";
$rp_data_privacy_policy = "Ratepay privacy policy ";
$rp_data_privacy_policy_url = "https://www.ratepay.com/legal-payment-dataprivacy";
$rp_sepa_notice_block_1 = "Note:";
$rp_sepa_notice_block_2 = "Once the contract has been concluded, Ratepay will send me the mandate reference.";
$rp_sepa_notice_block_3 = "I can request reimbursement of the amount debited within eight weeks, starting with the debit date. Applicable in this regard by the contract with my bank conditions.";
$wcd_sepa_notice_block = "Please provide your bank details for the monthly move-in on the 2nd of each calendar month. If this is on a Sunday or a public holiday, the move-in takes place on the following working day:";
$wcd_sepa_terms_please_note = "Note: ";
$wcd_sepa_terms_block_1 = "I hereby authorise Wirecard Bank AG to collect payments from my account by direct debit. At the same time, I authorise my bank to debit my account in accordance with the instructions from Wirecard Bank AG.";
$wcd_sepa_terms_block_2 = "As part of my rights, I am entitled to a refund from my bank under the terms and conditions of my agreement with my bank. A refund must be claimed within 8 weeks starting from the date on which my account was debited.";
$wcd_sepa_terms_block_3 = "My rights are explained in a statement that I can obtain from my bank.";
$rp_sepa_terms_block_21 = "I hereby authorise Ratepay GmbH to collect payments from my account by direct debit. At the same time, I authorise my bank to debit my account in accordance with the instructions from Ratepay GmbH.";
$rp_sepa_terms_block_22 = "Note: As part of my rights, I am entitled to a refund from my bank under the terms and conditions of my agreement with my bank. A refund must be claimed within 8 weeks starting from the date on which my account was debited.";
$rp_sepa_terms_block_23 = "My rights are explained in a statement that I can obtain from my bank.";
