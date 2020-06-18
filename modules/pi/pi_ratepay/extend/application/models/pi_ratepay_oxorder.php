<?php

class pi_ratepay_oxorder extends pi_ratepay_oxorder_parent
{
    /**
     * OX-19: Fix empty ordernr during Ratepay payment
     *
     * @param oxBasket      $oBasket        basket object
     * @param oxUserPayment $oUserpayment   user payment object
     *
     * @return  integer 2 or an error code
     */
    protected function _executePayment(oxBasket $oBasket, $oUserpayment)
    {
        if ($oUserpayment->oxuserpayments__oxpaymentsid->value  == "pi_ratepay_rate"
            || $oUserpayment->oxuserpayments__oxpaymentsid->value == "pi_ratepay_rechnung"
            || $oUserpayment->oxuserpayments__oxpaymentsid->value == "pi_ratepay_elv"
        ) {
            if (!$this->oxorder__oxordernr->value) {
                $this->_setNumber();
            } else {
                oxNew('oxCounter')->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
            }
        }

        return parent::_executePayment($oBasket, $oUserpayment);
    }
}
