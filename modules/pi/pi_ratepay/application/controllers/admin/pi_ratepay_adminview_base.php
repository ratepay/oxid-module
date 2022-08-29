<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

abstract class pi_ratepay_adminview_base extends oxAdminView
{
    /**
     * Returns oxid
     *
     * @param void
     * @return string
     */
    protected function _piGetOxid() {
        $oConfig = $this->getConfig();
        $sOxid = $oConfig->getRequestParameter("oxid");

        return $sOxid;
    }

    /**
     * Returns former saved id
     *
     * @param void
     * @return string
     */
    protected function _piGetSavedId()
    {
        $oConfig = $this->getConfig();
        $sSavedID = $oConfig->getRequestParameter("saved_oxid");

        return $sSavedID;
    }

    /**
     * Delete former saved id from session
     *
     * @param void
     * @return void
     */
    protected function _piDeleteSavedId()
    {
        $oSession = $this->getSession();
        $oSession->deleteVariable("saved_oxid");
    }

    /**
     * Check if checkbox has been set to on for given parameter.
     *
     * @param string $parameter
     * @return int 0 for false and 1 for true
     */
    protected function _isParameterCheckedOn($parameter)
    {
        $checked = 0;

        if ($parameter != null && $parameter == 'on') {
            $checked = 1;
        }

        return $checked;
    }

    /**
     * Check if checkbox has been set to on for given parameter.
     *
     * @param string $parameter
     * @return int 0 for false and 1 for true
     */
    protected function _isParameterCheckedYes($parameter)
    {
        $checked = 0;
        if ($parameter != null && $parameter == 'yes') {
            $checked = 1;
        }
        return $checked;
    }

}
