<?php

/**
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*/

class pi_ratepay_adminlist_base extends oxAdminList
{
    /**
     * (non-PHPdoc)
     * @see oxAdminList::render()
     */
    public function render() {
        parent::render();
        $oConfig = $this->getConfig();
        $sVersion = $oConfig->getVersion();
        $sNameConcat = $this->_piGetConcatByVersion();

        $this->_aViewData['shopversion'] = $sVersion;
        $this->_aViewData['nameconcat'] = $sNameConcat;
        $this->_aViewData['where'] = $this->_piGetEnteredValues();

        return $this->_sThisTemplate;
    }

    /**
     * returns an array of values that the user entered
     *
     * @return array
     */
    protected function _piGetEnteredValues()
    {
        $aReturn = array();
        $aWhere = $this->buildWhere();
        foreach ($aWhere as $sKey => $sValue) {
            $aValues = explode(' ', $sValue);
            $sValue = $aValues[0];
            $aSplittedKey = explode(".", $sKey);
            $sValue = $this->_piDecodeUrlSearchTerm($sValue);
            $aReturn[$aSplittedKey[0]][$aSplittedKey[1]] = $sValue;
        }
        return $aReturn;
    }

    /**
     * Prepares where-part to be able to find entries for call-/targeturi
     *
     * @param string $sInput
     * @return string
     */
    protected function _piDecodeUrlSearchTerm($sInput)
    {
        $sOutput = substr($sInput, 1,-1);
        $sOutput = urldecode($sOutput);
        $sOutput = urldecode($sOutput);
        $sOutput = str_replace("%", "", $sOutput);

        return $sOutput;
    }

    /**
     * Returns filter concat depending on oxid version
     *
     * @param string $sVersion
     * @return string
     */
    protected function _piGetConcatByVersion()
    {
        $sConcat = "][";
        return $sConcat;
    }

}