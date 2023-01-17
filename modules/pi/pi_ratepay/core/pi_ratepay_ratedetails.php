<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Model class for pi_ratepay_ratedetails table
 * @extends oxBase
 */
class pi_ratepay_RateDetails extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'pi_ratepay_RateDetails';

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('pi_ratepay_rate_details');
    }

    /**
     * Load data by orderid not oxid
     *
     * @param string $orderId
     * @return boolean
     */
    public function loadByOrderId($orderId)
    {
        //getting at least one field before lazy loading the object
        $this->_addField('oxid', 0);
        $selectQuery = $this->buildSelectString(array($this->getViewName() . ".orderid" => $orderId));

        return $this->_isLoaded = $this->assignRecord($selectQuery);
    }

}
