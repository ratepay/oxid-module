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
 * @package   PayIntelligent_RatePAY_Rate
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$sLangName = "English";

$piErrorAge = 'To make a payment via Ratepay Rate, you must be at least 18 years old.';
$piErrorBirth = 'To make a payment via Ratepay Rate, please provide your birth date.';
$piErrorPhone = 'To make a payment via Ratepay Rate, please provide your phone numer.';
$piErrorCompany = 'Please enter your company name and VAT ID.';
$piErrorBirthdayDigits = 'Please enter your year of birth in four digits. (e.g. 1982)';

$aLang = array(
    'charset'                                       => 'UTF-8',
    'PI_RATEPAY_RATE_VIEW_SANDBOX_NOTIFICATION'     => 'Testmode activated, please DONT use this payment method and get in contact with the merchant.',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_1'            => 'I have read and accepted  the ',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_2'            => '. I was informed about my ',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_3'            => '. ',
    'PI_RATEPAY_RATE_VIEW_POLICY_AGB'               => 'general terms and conditions',
    'PI_RATEPAY_RATE_VIEW_POLICY_WIDER'             => 'withdrawal',
    'PI_RATEPAY_RATE_VIEW_POLICY_PRIVACYPOLICY'     => 'Ratepay Data Privacy Statement',
    'PI_RATEPAY_RATE_ERROR'                         => 'Sorry, there is no payment with Ratepay possible. This decision was taken by Ratepay on the basis of an automated data processing algorithm. For Details, please read the ',
    'PI_RATEPAY_RATE_AGBERROR'                      => 'Please accept the conditions.',
    'PI_RATEPAY_RATE_SUCCESS'                       => 'Order completed successfully',
    'PI_RATEPAY_RATE_ERROR_BIRTH'                   => $piErrorBirth,
    'PI_RATEPAY_RATE_ERROR_PHONE'                   => $piErrorPhone,
    'PI_RATEPAY_RATE_ERROR_ADDRESS'                 => 'Please note that Ratepay Rate can only be used as a payment method when billing and shipping address entered are equal.',
    'PI_RATEPAY_RATE_ERROR_ZIP'                     => 'Please enter your correct zipcode.',
    'PI_RATEPAY_RATE_ERROR_AGE'                     => $piErrorAge,
    'PI_RATEPAY_RATE_VIEW_PAYMENT_FON'              => 'Fon:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_MOBILFON'         => 'Mobilfon:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE'        => 'Birthdate:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE_FORMAT' => '(dd.mm.yyyy)',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_FON_NOTE'         => 'Please insert Mobilfon or Telefonnumber.',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_COMPANY'          => 'Company:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_UST'              => 'UST-ID:',
    'PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS'         => $piErrorBirthdayDigits,
    'PI_RATEPAY_ERROR_COMPANY'                      => $piErrorCompany,
    'PI_RATEPAY_RATE_ERROR_CALCULATE_TO_PROCEED'    => 'Please create an installment plan to proceed.'
);
