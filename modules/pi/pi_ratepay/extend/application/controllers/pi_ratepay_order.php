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
 * @package   PayIntelligent_RatePAY
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */

/**
 * {@inheritdoc}
 *
 * Additionally sends RatePAY PAYMENT_REQUEST and sets RatePAY payment specific informations in db and session.
 *
 * @package PayIntelligent_RatePAY
 * @extends order
 */
class pi_ratepay_order extends pi_ratepay_order_parent
{
    /**
     * Check if this is a OXID 4.6.x Shop.
     * @return bool
     */
    public function piIsFourPointSixShop()
    {
        return substr(oxRegistry::getConfig()->getVersion(), 0, 3) === '4.6';
    }

    /**
     * Returns next order step. If ordering was sucessfull - returns string "thankyou" (possible
     * additional parameters), otherwise - returns string "payment" with additional
     * error parameters.
     *
     * @param integer $iSuccess status code
     *
     * @return  string  $sNextStep  partial parameter url for next step
     */
    protected function _getNextStep($iSuccess)
    {
        $nextStep = parent::_getNextStep($iSuccess);

        /**
         * OX-44 clean session payment data as the order got placed
         */
        if($nextStep == "thankyou"){
            $this->cleanSessionPaymentData();
        }

        return $nextStep;
    }

    /**
     * OX-44 clean ratepay session data
     */
    protected function cleanSessionPaymentData()
    {
        $variablesToClean = [
            'basketAmount',
            'bankOwner',
            'paymentid'
        ];
        $sessionVariables = array_keys($_SESSION);
        $sessionVariables = array_filter($sessionVariables, function($key) {
            return preg_match('/^pi_ratepay.*/', $key) == 1;
        });

        $session = (oxRegistry::getSession());
        $variablesToClean = array_merge($variablesToClean, $sessionVariables);

        foreach ($variablesToClean as $key) {
            if($session->hasVariable($key)) {
                $session->deleteVariable($key);
            }
        }
    }
}

