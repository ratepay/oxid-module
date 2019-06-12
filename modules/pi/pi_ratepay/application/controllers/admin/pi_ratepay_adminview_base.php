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
