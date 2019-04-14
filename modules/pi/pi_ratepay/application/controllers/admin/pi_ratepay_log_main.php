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

/**
 * RatePAY Logging View
 *
 * Shows RatePAY Transaction Logs
 *
 * Also:
 * {@inheritdoc}
 *
 * @package PayIntelligent_RatePAY
 * @extends oxAdminView
 */
class pi_ratepay_log_main extends oxAdminView
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'pi_ratepay_log_main.tpl';

    /**
     * Name of chosen object class (default null).
     * @var string
     */
    protected $_sModelClass = 'pi_ratepay_logs';

    /**
     * DB Table
     * @var string
     */
    protected $_sTable = 'pi_ratepay_logs';

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

            $oRatePayLogs = oxNew(
                $this->_sModelClass,
                getViewName($this->_sModelClass)
            );
            $oRatePayLogs->load($sOxid);

            $this->_aViewData["edit"] =  $oRatePayLogs;
        }

        return $this->_sThisTemplate;
    }

    /**
     * Removes all log entries from db which are older than x days.
     *
     * @param void
     * @return void
     */
    public function deleteLogs()
    {
        $oConfig = $this->getConfig();
        $oDb = oxDb::getDb();
        $sLogDays = $oConfig->getRequestParameter('logdays');
        $iLogDays = (int) $sLogDays;

        $sQuery = "DELETE FROM ".$this->_sTable;

        if ($iLogDays > 0) {
            $sQuery .= " WHERE TO_DAYS(now()) - TO_DAYS(date) > ".$iLogDays;
        }

        $oDb->execute($sQuery);
        $this->addTplParam('deleteSuccess', 'Success');
    }

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
}
