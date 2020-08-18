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
$sLangName = "Niederländisch";

$piErrorAge = 'Om door Ratepay een betaling op rekening door te kunnen, voeren moet u ten minste 18 jaar of ouder zijn.';
$piErrorBirth = 'Om door Ratepay een betaling op rekening door te kunnen voeren, gelieve hier uw geboortedatum invoeren.';
$piErrorPhone = 'Om door Ratepay een betaling op rekening door te kunnen voeren, gelieve hier uw telefoonnummer invoeren.';
$piErrorCompany = 'Gelieve hier uw bedrijfsnaam en uw btw-nummer invoeren.';
$piErrorBirthdayDigits = 'Gelieve hier uw geboortedatum middels een viercijferige invoer vastleggen (bijv. 1982)';

$aLang = array(
    'charset'                                       => 'UTF-8',
    'PI_RATEPAY_ELV_VIEW_SANDBOX_NOTIFICATION'      => 'Testmode activated, please DONT use this payment method and get in contact with the merchant.',
    'PI_RATEPAY_ELV_VIEW_CREDITOR_ID_TEXT'          => 'Incassant ID',
    'PI_RATEPAY_ELV_VIEW_CREDITOR_ID_VALUE'         => 'DE39RPY00000568463',
    'PI_RATEPAY_ELV_VIEW_MANDATE_TEXT'              => 'Kenmerk machtiging',
    'PI_RATEPAY_ELV_VIEW_MANDATE_VALUE'             => '(wordt na aankoop medegedeeld)',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT'         => 'Einwilligungserklärung zum SEPA-Mandat lesen',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_1'  => 'Ich erm&auml;chtige die Ratepay GmbH, Zahlungen von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von der Ratepay GmbH auf mein Konto gezogenen Lastschriften einzul&ouml;sen.',
    'PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_2'  => 'Hinweis: Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.',
    'PI_RATEPAY_ELV_VIEW_POLICY_OWNERPOLICY'        => 'H&auml;ndler-Datenschutzerkl&auml;rung',
    'PI_RATEPAY_ELV_VIEW_POLICY_TEXT_6'             => ' und bin insbesondere damit einverstanden, zum Zwecke der Durchf&uuml;hrung des Vertrags &uuml;ber die von mir angegebene E-Mail-Adresse kontaktiert zu werden.',
    'PI_RATEPAY_ELV_VIEW_POLICY_PRIVACYPOLICY'      => 'Ratepay Privacybeleid',
    'PI_RATEPAY_ELV_ERROR'                          => 'Helaas is een betaling met de gekozen betaalmethode Incasso niet mogelijk. Dit besluit is genomen op basis van een geautomatiseerde gegevenscontrole. Details vindt u onder ',
    'PI_RATEPAY_ELV_AGBERROR'                       => 'Gelieve hier akkoord gaan met de voorwaarden.',
    'PI_RATEPAY_ELV_SUCCESS'                        => 'Uw bestelling is succelvol afgerond',
    'PI_RATEPAY_ELV_ERROR_ADDRESS'                  => 'Houdt u er rekening mee dat uw factuur- en afleveradres aan elkaar gelijk moeten zijn om een aankoop middels Ratepay SEPA-incasso door te kunnen voeren.',
    'PI_RATEPAY_ELV_ERROR_ZIP'                      => 'Bitte geben Sie Ihre korrekte Postleitzahl ein.',
    'PI_RATEPAY_ELV_ERROR_BIRTH'                    => $piErrorBirth,
    'PI_RATEPAY_ELV_ERROR_PHONE'                    => $piErrorPhone,
    'PI_RATEPAY_ELV_ERROR_AGE'                      => $piErrorAge,
    'PI_RATEPAY_ELV_VIEW_PAYMENT_FON'               => 'Telefoonnummer:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_MOBILFON'          => 'Mobiltelefon:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE'         => 'Geboortedatum:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE_FORMAT'  => '(tt.mm.jjjj)',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_FON_NOTE'          => 'Gelieve hier uw telefoonnummer invoeren.',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_COMPANY'           => 'Firma:',
    'PI_RATEPAY_ELV_VIEW_PAYMENT_UST'               => 'USt-IdNr.',
    'PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS'         => $piErrorBirthdayDigits,
    'PI_RATEPAY_ERROR_COMPANY'                      => $piErrorCompany,
    'PI_RATEPAY_ELV_ERROR_OWNER'                    => 'Om een betaling via Ratepay SEPA-incasso door te voeren, gelieve hier de naam rekening houder invoeren. ',
    'PI_RATEPAY_ELV_ERROR_ACCOUNT_NUMBER'           => 'Bitte geben Sie eine korrekte IBAN/Kontonummer ein.',
    'PI_RATEPAY_ELV_ERROR_NAME'                     => 'Um eine Zahlung per Ratepay SEPA-Lastschrift durchzuf&uuml;hren, geben Sie bitte den Banknamen ein.',
    'PI_RATEPAY_ELV_VIEW_BANK_OWNER'                => 'Rekeninghouder',
    'PI_RATEPAY_ELV_VIEW_BANK_ACCOUNT_NUMBER'       => 'Rekeningnummer',
    'PI_RATEPAY_ELV_VIEW_BANK_IBAN'                 => 'IBAN',
    'PI_RATEPAY_ELV_VIEW_BANK_NAME'                 => 'Kredietinstelling',
    'PI_RATEPAY_ELV_ERROR_BANKCODE_TO_SHORT'        => 'Die Bankleitzahl muss acht Zeichen lang sein.',
    'PI_RATEPAY_ELV_VIEW_BANK_CODE'                 => "BLZ:",
);
