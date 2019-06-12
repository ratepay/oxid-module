<?php


class pi_ratepay_log_list extends pi_ratepay_adminlist_base
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'pi_ratepay_log_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'pi_ratepay_logs';


    /**
     * Sets default list sorting field and executes parent method parent::Init().
     *
     * @return null
     */
    public function init() {
        $oConfig = $this->getConfig();
        $this->_sDefSort = "DATE";
        $sSortCol = $oConfig->getRequestParameter('sort');

        if (!$sSortCol || $sSortCol == $this->_sDefSort) {
            $this->_blDesc = false;
        }

        parent::init();
    }
}