<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Model class for pi_ratepay_orders table
 * @extends oxBase
 */
class pi_ratepay_Orders extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'pi_ratepay_Orders';

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('pi_ratepay_orders');
    }

    /**
     * Load data by order_number not oxid
     *
     * @param string $orderNumber
     * @return boolean
     */
    public function loadByOrderNumber($orderNumber)
    {
        //getting at least one field before lazy loading the object
        $this->_addField('oxid', 0);
        $selectQuery = $this->buildSelectString(array($this->getViewName() . ".order_number" => $orderNumber));

        return $this->_isLoaded = $this->assignRecord($selectQuery);
    }

}
