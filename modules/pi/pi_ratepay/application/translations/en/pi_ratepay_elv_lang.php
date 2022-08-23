<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  PayIntelligent
 * @package   PayIntelligent_RatePAY_Elv
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$sLangName = "English";

$piErrorAge = 'To make a payment via Ratepay Lastschrift, you must be at least 18 years old.';
$piErrorBirth = 'To make a payment via Ratepay Lastschrift, please provide your birth date.';
$piErrorPhone = 'To make a payment via Ratepay Lastschrift, please provide your phone numer.';
$piErrorCompany = 'Please enter your company name and VAT ID.';
$piErrorBirthdayDigits = 'Please enter your year of birth in four digits. (e.g. 1982)';

$aLang = array(
    'charset'                                       => 'UTF-8',
    'PI_RATEPAY_ELV_VIEW_SANDBOX_NOTIFICATION'      => 'Testmode activated, please DONT use this payment method and get in contact with the merchant.',
    'PI_RATEPAY_ELV_VIEW_MAND_CRED_NOTIFICATION'    => 'The mandate reference and creditor identifier will be communicated to you separately.',
    'PI_RATEPAY_ELV_VIEW_CREDITOR_ID_TEXT'          => 'Creditor ID',
    'PI_RATEPAY_ELV_VIEW_CREDITOR_ID_VALUE'         => 'DE39RPY00000568463',
    'PI_RATEPAY_ELV_VIEW_MANDATE_TEXT'              => 'Mandate Reference',
    'PI_RATEPAY_ELV_VIEW_MANDATE_VALUE'             => '(will be sent after the purchase is completed)',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT'         => 'Read agreement to the SEPA mandate',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_1'  => 'I hereby authorise Ratepay GmbH to collect payments from my account by direct debit. At the same time, I authorise my bank to debit my account in accordance with the instructions from Ratepay GmbH.',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_2'  => 'Note: As part of my rights, I am entitled to a refund from my bank under the terms and conditions of my agreement with my bank. A refund must be claimed within 8 weeks starting from the date on which my account was debited. My rights are explained in a statement that I can obtain from my bank.',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_1'             => 'I have read and accepted  the ',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_2'             => '. I was informed about my ',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_3'             => '. ',
    'PI_RATEPAY_ELV_VIEW_POLICY_AGB'                => 'general terms and conditions',
    'PI_RATEPAY_ELV_VIEW_POLICY_WIDER'              => 'withdrawal',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_4'             => 'In addition I also agree that my personal data is utilized by Ratepay according to the ',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_5'             => ' and ',
    'PI_RATEPAY_ELV_VIEW_POLICY_OWNERPOLICY'        => 'Shop Data Privacy Statement',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_6'             => '. In order to accomplish the contract I agree in particular to be contacted by all parties involved via my email address provided.',
    'PI_RATEPAY_ELV_VIEW_POLICY_PRIVACYPOLICY'      => 'Ratepay Data Privacy Statement',
    'PI_RATEPAY_ELV_ERROR'                          => 'Sorry, there is no payment with Ratepay possible. This decision was taken by Ratepay on the basis of an automated data processing algorithm. For Details, please read the ',
    'PI_RATEPAY_ELV_AGBERROR'                       => 'Please accept the conditions.',
    'PI_RATEPAY_ELV_SUCCESS'                        => 'Order completed successfully',
    'PI_RATEPAY_ELV_ERROR_ADDRESS'                  => 'Please note that Ratepay SEPA-Lastschrift can only be used as a payment method when billing and shipping address entered are equal.',
    'PI_RATEPAY_ELV_ERROR_ZIP'                      => 'Please enter your correct zipcode.',
    'PI_RATEPAY_ELV_ERROR_BIRTH'                    => $piErrorBirth,
    'PI_RATEPAY_ELV_ERROR_PHONE'                    => $piErrorPhone,
    'PI_RATEPAY_ELV_ERROR_AGE'                      => $piErrorAge,
    'PI_RATEPAY_ELV_VIEW_PAYMENT_FON'               => 'Phone:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_MOBILFON'          => 'Mobile phone:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE'         => 'Birthdate:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE_FORMAT'  => '(dd.mm.yyyy)',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_FON_NOTE'          => 'Please insert phone number (mobile or landline).',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_COMPANY'           => 'Company:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_UST'               => 'Vat ID No:',
    'PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS'         => $piErrorBirthdayDigits,
    'PI_RATEPAY_ERROR_COMPANY'                      => $piErrorCompany,
    'PI_RATEPAY_ELV_ERROR_OWNER'                    => 'To make a payment via Ratepay SEPA direct debit, please enter the name of the account owner.',
    'PI_RATEPAY_ELV_ERROR_ACCOUNT_NUMBER'           => 'Please give a valid IBAN number.',
    'PI_RATEPAY_ELV_ERROR_NAME'                     => 'To make a payment by Ratepay SEPA direct debit, please enter the name of the bank.',
    'PI_RATEPAY_ELV_VIEW_BANK_OWNER'                => 'Account owner',
    'PI_RATEPAY_ELV_VIEW_BANK_ACCOUNT_NUMBER'       => 'Account number',
    'PI_RATEPAY_ELV_VIEW_BANK_IBAN'                 => 'IBAN',
    'PI_RATEPAY_ELV_VIEW_BANK_NAME'                 => 'Bank name',
    'PI_RATEPAY_ELV_ERROR_BANKCODE_TO_SHORT'        => 'The bank code (BIC) must be eight characters long.',
    'PI_RATEPAY_ELV_VIEW_BANK_CODE'                 => 'BIC:',
    'PI_RATEPAY_ELV_SEPA_ACCOUNT_INFORMATION'       => 'SEPA Account Information',
    'PI_RATEPAY_ELV_CLASSIC_ACCOUNT_INFORMATION'    => 'Classic Account Information',
);
