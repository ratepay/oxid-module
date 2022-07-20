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
$aLang = array(
    'charset'                                => 'UTF-8',
    'PI_RATEPAY_ELV_PDF_OWNER'               => "Director:",
    'PI_RATEPAY_ELV_PDF_FON'                 => "Phone:",
    'PI_RATEPAY_ELV_PDF_FAX'                 => "Fax:",
    'PI_RATEPAY_ELV_PDF_EMAIL'               => "E-Mail:",
    'PI_RATEPAY_ELV_PDF_COURT'               => "District Court:",
    'PI_RATEPAY_ELV_PDF_HR'                  => "HR:",
    'PI_RATEPAY_ELV_PDF_BULL'                => " • ",
    'PI_RATEPAY_ELV_PDF_ACCOUNTHOLDER'       => "Accountowner:",
    'PI_RATEPAY_ELV_PDF_BANKNAME'            => "Bank:",
    'PI_RATEPAY_ELV_PDF_BANKCODENUMBER'      => "Bank code number:",
    'PI_RATEPAY_ELV_PDF_ACCOUNTNUMBER'       => "Account number:",
    'PI_RATEPAY_ELV_PDF_IBAN'                => "IBAN:",
    'PI_RATEPAY_ELV_PDF_INTERNATIONALDESC'   => "For the international payment transfer:",
    'PI_RATEPAY_ELV_PDF_PAYTRANSFER'         => "Please transfer the above amount to the following account:",
    'PI_RATEPAY_ELV_PDF_PAYUNTIL_1'          => "The invoice amount due will be debited from your account, which you specified during the ordering process ",
    'PI_RATEPAY_ELV_PDF_PAYUNTIL_2'          => "",
    'PI_RATEPAY_ELV_PDF_PAYUNTIL_3'          => "The following terms of payment apply: ",
    'PI_RATEPAY_ELV_PDF_PAYUNTIL_4'          => " days after the invoice date without deduction.",
    'PI_RATEPAY_ELV_PDF_REFERENCE'           => "Reference:",
    'PI_RATEPAY_ELV_PDF_ADDITIONALINFO_1'    => "Payment is processed by Ratepay GmbH. The seller has assigned the due purchase price claim from",
    'PI_RATEPAY_ELV_PDF_ADDITIONALINFO_2'    => "your order, including any ancillary claims, to Ratepay GmbH. The owner of the claim",
    'PI_RATEPAY_ELV_PDF_ADDITIONALINFO_3'    => "is Ratepay GmbH. According to Section 407 of the German Civil Code,",
    'PI_RATEPAY_ELV_PDF_ADDITIONALINFO_4'    => "you can only make a debt-discharging payment to Ratepay GmbH.",
    'PI_RATEPAY_ELV_PDF_ABOVEARTICLE'        => "For your purchase on the account we will charge you the following products:",
    'PI_RATEPAY_ELV_PDF_RATEPAYID'           => "Ratepay-Reference number:",
    'PI_RATEPAY_ELV_PDF_PAYMETHOD'           => "Payment Method:",
    'PI_RATEPAY_ELV_SETTINGS'                => 'Rechnung Settings',
    'PI_RATEPAY_ELV_SETTINGS_TITLE'          => 'Ratepay Rechnung Settings',
    'PI_RATEPAY_ELV_SETTINGS_BANKTITLE'      => 'Ratepay Banking Details',
    'PI_RATEPAY_ELV_SETTINGS_PROFILEID'      => 'Profil ID',
    'PI_RATEPAY_ELV_SETTINGS_SECURITYCODE'   => 'Security Code',
    'PI_RATEPAY_ELV_SETTINGS_ACCOUNTHOLDER'  => 'Company',
    'PI_RATEPAY_ELV_SETTINGS_BANKNAME'       => 'Bank',
    'PI_RATEPAY_ELV_SETTINGS_BANKCODENUMBER' => 'Bank code number',
    'PI_RATEPAY_ELV_SETTINGS_ACCOUNTNUMBER'  => 'Account number',
    'PI_RATEPAY_ELV_SETTINGS_IBAN'           => 'IBAN',
    'PI_RATEPAY_ELV_SETTINGS_INVOICEFIELD'   => 'Additional Field',
    'PI_RATEPAY_ELV_SETTINGS_SANDBOX'        => 'Sandbox',
    'PI_RATEPAY_ELV_SETTINGS_SAVE'           => 'Save',
    'PI_RATEPAY_ELV_SETTINGS_AGB'            => 'General terms and conditions URL',
    'PI_RATEPAY_ELV_SETTINGS_RATEPAY'        => 'Ratepay-Policy URL',
    'PI_RATEPAY_ELV_SETTINGS_POLICY'         => 'Merchant-Policy URL',
    'PI_RATEPAY_ELV_SETTINGS_WIDER'          => 'Right of withdrawal URL',
    'PI_RATEPAY_ELV_LOGGING'                 => 'Logging',
    'PI_RATEPAY_ELV_ELV'                     => 'Ratepay SEPA-Lastschrift'
);
