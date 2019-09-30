<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 27.09.2019
 * Time: 20:00
 */

class pi_ratepay_order extends pi_ratepay_order_parent
{
    /**
     * Returns formatted credit amount
     *
     * @return string
     */
    public function ratepayGetFormattedCreditAmount()
    {
        return oxRegistry::getLang()->formatCurrency($this->oxorder__ratepaycreditamount->value, $this->getOrderCurrency());
    }
}