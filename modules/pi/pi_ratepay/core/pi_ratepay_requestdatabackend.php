<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Data provider for backend operation.
 * @extends pi_ratepay_RequestAbstract
 */
class pi_ratepay_RequestDataBackend extends pi_ratepay_RequestAbstract
{
    /**
     * Order Object
     * @var oxorder
     */
    private $_order;

    /**
     * Class constructor
     * @param oxorder $order
     */
    public function __construct($order)
    {
        $this->_order = $order;
    }

    /**
     * Generate oxuser from order user.
     * @inheritdoc
     * @return oxuser
     */
    public function getUser()
    {
        $ratepayOrder = oxNew('pi_ratepay_orders');
        $ratepayOrder->loadByOrderNumber($this->_order->getId());
        $orderUser = $this->_order->getOrderUser();
        $orderUser->oxuser__oxbirthdate = clone $ratepayOrder->pi_ratepay_orders__userbirthdate;

        return $orderUser;
    }
}
