<?php

function ratepayAutoload($sClass)
{
    startProfile("ratepayAutoload");
    if (strpos($sClass, 'RatePAY') !== false) {
        static $aClassPaths;

        if (isset($aClassPaths[$sClass])) {
            stopProfile("ratepayAutoload");
            include $aClassPaths[$sClass];

            return;
        }

        $sClass = strtolower($sClass);
        $aModuleFiles = oxUtilsObject::getInstance()->getModuleVar('aModuleFiles');
        if (is_array($aModuleFiles)) {
            $sBasePath = getShopBasePath();
            $oModulelist = oxNew('oxmodulelist');
            $aActiveModuleInfo = $oModulelist->getActiveModuleInfo();
            if (is_array($aActiveModuleInfo)) {
                foreach ($aModuleFiles as $sModuleId => $aModules) {
                    if (isset($aModules[$sClass]) && isset($aActiveModuleInfo[$sModuleId])) {
                        $sPath = $aModules[$sClass];
                        $sFilename = $sBasePath . 'modules/' . $sPath;
                        if (file_exists($sFilename)) {
                            if (!isset($aClassPaths[$sClass])) {
                                $aClassPaths[$sClass] = $sFilename;
                            }
                            stopProfile("ratepayAutoload");
                            include $sFilename;

                            return;
                        }
                    }
                }
            }
        }
    }

    stopProfile("ratepayAutoload");
}