<?php

/**
 * Class pi_ratepay_shopcontrol
 *
 * extends OXID ShopControl core class to enable loading a custom autoloader
 * see details in code below
 */
class pi_ratepay_shopcontrol extends pi_ratepay_shopcontrol_parent
{
    public function start($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        /**
         * OXID-169
         * If the classes using backslash namespace format are not found
         * this might be due to using Windows system, handling filepath differently.
         * Then we call a specific class autoloader for those classes
         */
        if (!class_exists('RatePAY\RequestBuilder')) {
            require __DIR__ . '/../autoloader.php';
            spl_autoload_register('ratepayAutoload');
        }

        return parent::start($sClass, $sFunction, $aParams, $aViewsChain);
    }
}
