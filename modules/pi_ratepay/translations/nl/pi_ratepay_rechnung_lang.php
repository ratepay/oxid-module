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
 * @package   PayIntelligent_RatePAY_Rechnung
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$sLangName = "Niederländisch";

$piErrorAge = 'Om door RatePAY een betaling op rekening door te kunnen, voeren moet u ten minste 18 jaar of ouder zijn.';
$piErrorBirth = 'Om door RatePAY een betaling op rekening door te kunnen voeren, gelieve hier uw geboortedatum invoeren.';
$piErrorPhone = 'Om door RatePAY een betaling op rekening door te kunnen voeren, gelieve hier uw telefoonnummer invoeren.';
$piErrorCompany = 'Gelieve hier uw bedrijfsnaam en uw btw-nummer invoeren.';
$piErrorBirthdayDigits = 'Gelieve hier uw geboortedatum middels een viercijferige invoer vastleggen (bijv. 1982)';

$aLang = array(
    'charset'                                            => 'UTF-8',
    'PI_RATEPAY_RECHNUNG_VIEW_SANDBOX_NOTIFICATION'      => 'Testmode activated, please DONT use this payment method and get in contact with the merchant.',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_1'             => 'Ik verklaar de ',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_2'             => ' te hebben gelezen en goedgekeurd. Ik ben op de hoogte gesteld van mijn ',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_3'             => ' informiert.',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_AGB'                => 'algemene voorwaarden',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_WIDER'              => 'herroepingsrecht',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_4'             => 'en geef toestemming tot het gebruik van mijn gegevens volgens het ',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_5'             => ' evenals van ',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_OWNERPOLICY'        => 'handelaar-privacybeleid',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_TEXT_6'             => ' en in het bijzonder geef ik toestemming tot contactopname via e-mail als deze betrekking heeft op de afhandeling van mijn overeenkomst.',
    'PI_RATEPAY_RECHNUNG_VIEW_POLICY_PRIVACYPOLICY'      => 'RatePAY Privacybeleid',
    'PI_RATEPAY_RECHNUNG_ERROR'                          => 'Helaas is een betaling met de gekozen betaalmethode Factuur niet mogelijk. Dit besluit is genomen op basis van een geautomatiseerde gegevenscontrole. Details vindt u onder ',
    'PI_RATEPAY_RECHNUNG_AGBERROR'                       => 'Gelieve hier akkoord gaan met de voorwaarden.',
    'PI_RATEPAY_RECHNUNG_SUCCESS'                        => 'Uw bestelling is succelvol afgerond',
    'PI_RATEPAY_RECHNUNG_ERROR_ADDRESS'                  => 'BHoudt u er rekening mee dat uw factuur- en afleveradres overeenstemmend moeten zijn om een aankoop middels RatePAY SEPA-incasso door te kunnen voeren.',
    'PI_RATEPAY_RECHNUNG_ERROR_ZIP'                      => 'Bitte geben Sie Ihre korrekte Postleitzahl ein.',
    'PI_RATEPAY_RECHNUNG_ERROR_BIRTH'                    => $piErrorBirth,
    'PI_RATEPAY_RECHNUNG_ERROR_PHONE'                    => $piErrorPhone,
    'PI_RATEPAY_RECHNUNG_ERROR_AGE'                      => $piErrorAge,
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_FON'               => 'Telefoonnummer:',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_MOBILFON'          => 'Mobiltelefon:',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_BIRTHDATE'         => 'Geboortedatum:',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_BIRTHDATE_FORMAT'  => '(tt.mm.jjjj)',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_FON_NOTE'          => 'Gelieve hier uw telefoonnummer invoeren.',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_COMPANY'           => 'Firma:',
    'PI_RATEPAY_RECHNUNG_VIEW_PAYMENT_UST'               => 'USt-IdNr.',
    'PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS'              => $piErrorBirthdayDigits,
    'PI_RATEPAY_ERROR_COMPANY'                           => $piErrorCompany
);
