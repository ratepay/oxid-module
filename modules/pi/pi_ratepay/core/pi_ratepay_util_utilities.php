<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pi_ratepay_util
 *
 * @author enes
 */
class pi_ratepay_util_Utilities
{

    /**
     * Static array of RatePAY payment methods.
     * @var array
     */
    public static $_RATEPAY_PAYMENT_METHOD = array(
        'pi_ratepay_rechnung',
        'pi_ratepay_rate',
        'pi_ratepay_elv'
    ); // 'pi_ratepay_vorkasse'
    public static $_RATEPAY_PAYMENT_METHOD_NAMES = array(
        'invoice' => "rechnung",
        'installment' => "rate",
        'elv' => "elv"
    ); // 'prepayment' => "vorkasse"

    public static $_RATEPAY_PRIVACY_NOTICE_URL_DACH = 'https://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis-dach';

    public static $_RATEPAY_PRIVACY_NOTICE_URL_NL =  'https://www.ratepay.com/aanvullende-voorwaarden-en-privacybeleid-nl';


    /**
     * Static array of supported countries.
     * @var array
     */
    public static $_RATEPAY_ALLOWED_COUNTRIES = array('de', 'at', 'ch', 'nl');

    const PI_MODULE_VERSION = '5.0.0';

    public static function getPaymentMethod($paymentType)
    {
        $paymentMethod = null;
        switch ($paymentType) {
            case 'pi_ratepay_rechnung':
                $paymentMethod = 'INVOICE';
                break;
            case 'pi_ratepay_rate':
                $paymentMethod = 'INSTALLMENT';
                break;
            case 'pi_ratepay_elv':
                $paymentMethod = 'ELV';
                break;
            default:
                break;
        }

        return $paymentMethod;
    }

    public static function getCountry($countryId)
    {
        return strtolower(oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $countryId . "'"));
    }

    /**
     * Get formattet number
     * @param string $str
     * @param int $decimal
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    public function getFormattedNumber($str, $decimal = 2, $dec_point = ".", $thousands_sep = "") {
        if(strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }

        return number_format($str, $decimal, $dec_point, $thousands_sep);
    }
}
