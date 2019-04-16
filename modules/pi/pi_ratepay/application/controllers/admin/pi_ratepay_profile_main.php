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
class pi_ratepay_profile_main extends pi_ratepay_adminview_base
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'pi_ratepay_profile_main.tpl';

    /**
     * Name of chosen object class (default null).
     * @var string
     */
    protected $_sModelClass = 'pi_ratepay_settings';

    /**
     * DB Table
     * @var string
     */
    protected $_sTable = 'pi_ratepay_settings';


    /**
     * Handle displaying model entry
     *
     * @param void
     * @return string
     */
    public function render() {
        parent::render();
        $sSavedID  = $this->_piGetSavedId();
        $sOxid = $this->_piGetOxid();

        $blNotLoaded = (
            (
                $sOxid == "-1" ||
                !isset($sOxid)
            ) &&
            isset($sSavedID)
        );
        $blLoaded = (
            $sOxid != "-1" &&
            isset($sOxid)
        );

        if ($blNotLoaded) {
            $sOxid = $sSavedID;
            $this->_aViewData["oxid"] =  $sOxid;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if ($blLoaded) {
            // load object
            $this->_piDeleteSavedId();

            $oProfile = oxNew(
                $this->_sModelClass,
                getViewName($this->_sModelClass)
            );
            $oProfile->load($sOxid);

            $this->_aViewData["edit"] =  $oProfile;
        }

        return $this->_sThisTemplate;
    }
}
