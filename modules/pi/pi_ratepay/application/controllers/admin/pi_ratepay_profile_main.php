<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
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
