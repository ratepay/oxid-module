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
 * @category  pi_ratepay_core
 * @package   pi_ratepay
 * @copyright (C) 2020 Fatchip GmbH  <http://www.fatchip.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */

/**
 * Model class for pi_ratepay_payment_ban table
 * @extends oxBase
 */
class pi_ratepay_PaymentBan extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'pi_ratepay_PaymentBan';

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('pi_ratepay_payment_ban');
    }

    /**
     * Load data by key userid_paymentMethod, not oxid
     *
     * @param string $userid
     * @param string $paymentMethod
     * @return bool
     */
    public function loadByUserAndMethod($userid, $paymentMethod)
    {
        //getting at least one field before lazy loading the object
        $this->_addField('OXID', 0);
        $selectQuery = $this->buildSelectString(array($this->getViewName() . ".USERID" => $userid, $this->getViewName() . ".PAYMENT_METHOD" => $paymentMethod));

        return $this->_isLoaded = $this->assignRecord($selectQuery);
    }

}
