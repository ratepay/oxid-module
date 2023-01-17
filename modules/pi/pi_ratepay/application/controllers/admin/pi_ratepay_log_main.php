<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
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
class pi_ratepay_log_main extends pi_ratepay_adminview_base
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
}
