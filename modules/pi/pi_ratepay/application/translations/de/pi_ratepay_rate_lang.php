<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$sLangName = "Deutsch";

$piErrorAge = 'Um eine Zahlung per Ratepay Rate durchzuf&uuml;hren, m&uuml;ssen Sie mindestens 18 Jahre alt sein.';
$piErrorBirth = 'Um eine Zahlung per Ratepay Rate durchzuf&uuml;hren, geben Sie bitte Ihr Geburtsdatum ein.';
$piErrorPhone = 'Um eine Zahlung per Ratepay Rate durchzuf&uuml;hren, geben Sie bitte Ihre Telefonnummer ein.';
$piErrorCompany = 'Geben Sie bitte Ihren Firmennamen und Ihre Umsatzsteuer-ID ein.';
$piErrorBirthdayDigits = 'Geben Sie bitte Ihr Geburtsjahr vierstellig ein. (z.B. 1982)';

$aLang = array(
    'charset'                                       => 'UTF-8',
    'PI_RATEPAY_RATE_VIEW_SANDBOX_NOTIFICATION'     => 'Testmodus aktiviert, bitte nutzen Sie diese Zahlart NICHT f&uuml;r die Bestellung und informieren den H&auml;ndler &uuml;ber diese Nachricht.',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_1'            => 'Ich habe die ',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_2'            => ' zur Kenntnis genommen und erkl&auml;re mich mit deren Geltung einverstanden. Ich wurde &uuml;ber mein ',
    'PI_RATEPAY_RATE_VIEW_POLICY_TEXT_3'            => ' informiert.',
    'PI_RATEPAY_RATE_VIEW_POLICY_AGB'               => 'Allgemeinen Gesch&auml;ftsbedingungen',
    'PI_RATEPAY_RATE_VIEW_POLICY_WIDER'             => 'Widerrufsrecht',
    'PI_RATEPAY_RATE_VIEW_POLICY_PRIVACYPOLICY'     => 'Ratepay-Datenschutzerkl&auml;rung',
    'PI_RATEPAY_RATE_ERROR'                         => 'Leider ist eine Bezahlung mit Ratepay nicht m&ouml;glich. Diese Entscheidung ist von Ratepay auf der Grundlage einer automatisierten Datenverarbeitung getroffen worden. Einzelheiten erfahren Sie in der ',
    'PI_RATEPAY_RATE_ERROR_ADDRESS'                 => 'Bitte beachten Sie, dass Ratepay Rate nur genutzt werden kann, wenn Rechnungs- und Lieferaddresse identisch sind.',
    'PI_RATEPAY_RATE_ERROR_ZIP'                     => 'Bitte geben Sie Ihre korrekte Postleitzahl ein.',
    'PI_RATEPAY_RATE_ERROR_BIRTH'                   => $piErrorBirth,
    'PI_RATEPAY_RATE_ERROR_PHONE'                   => $piErrorPhone,
    'PI_RATEPAY_RATE_AGBERROR'                      => 'Bitte akzeptieren Sie die Bedingungen.',
    'PI_RATEPAY_RATE_SUCCESS'                       => 'Bestellung erfolgreich abgeschlossen',
    'PI_RATEPAY_RATE_ERROR_AGE'                     => $piErrorAge,
    'PI_RATEPAY_RATE_VIEW_PAYMENT_FON'              => 'Telefon:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_MOBILFON'         => 'Mobiltelefon:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE'        => 'Geburtsdatum:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE_FORMAT' => '(tt.mm.jjjj)',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_FON_NOTE'         => 'Tragen Sie bitte entweder Ihr Telefon oder Mobiltelefon ein.',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_COMPANY'          => 'Firma:',
    'PI_RATEPAY_RATE_VIEW_PAYMENT_UST'              => 'UST-ID:',
    'PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS'         => $piErrorBirthdayDigits,
    'PI_RATEPAY_ERROR_COMPANY'                      => $piErrorCompany,
    'PI_RATEPAY_RATE_ERROR_CALCULATE_TO_PROCEED'    => 'Um Fortfahren zu können erstellen Sie bitte zuerst einen Ratenplan.'
);
