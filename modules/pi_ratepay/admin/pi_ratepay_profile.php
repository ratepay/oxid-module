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
class pi_ratepay_Profile extends pi_ratepay_admin_SettingsAbstract
{
    protected $_tableName = 'pi_ratepay_settings';

    public function render()
    {
        parent::render();

        $countries = pi_ratepay_util_utilities::$_RATEPAY_ALLOWED_COUNTRIES;
        $methods = pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD_NAMES;

        $config = array();
        $activeCountries = array('de' => 'de');
        $shopId = $this->getConfig()->getShopId();

        $settings = oxNew('pi_ratepay_settings');
        $shopId = $settings->setShopIdToOne($shopId);

        foreach ($countries as $country) {
            foreach ($methods as $methodDB => $methodShop) {
                $settings->loadByType($methodDB, $shopId, $country);

                $config[$country][$methodShop]['active'] = (bool) $settings->pi_ratepay_settings__active->rawValue;
                $config[$country][$methodShop]['profile_id'] = $settings->pi_ratepay_settings__profile_id->rawValue;
                $config[$country][$methodShop]['security_code'] = $settings->pi_ratepay_settings__security_code->rawValue;
                $config[$country][$methodShop]['sandbox'] = (bool) $settings->pi_ratepay_settings__sandbox->rawValue;
                $config[$country][$methodShop]['logging'] = (bool) $settings->pi_ratepay_settings__logging->rawValue;
                $config[$country][$methodShop]['whitelabel'] = (bool) $settings->pi_ratepay_settings__whitelabel->rawValue;
                $config[$country][$methodShop]['duedate'] = $settings->pi_ratepay_settings__duedate->rawValue;

                if (!empty($config[$country][$methodShop]['profile_id']) && !empty($config[$country][$methodShop]['security_code'])) {
                    $extendedData = array('profileId' => $config[$country][$methodShop]['profile_id'], 'securityCode' => $config[$country][$methodShop]['security_code'], 'country' => $country);
                    $profileRequest = $this->_callProfileRequest('pi_ratepay_' . $methodShop, $extendedData);

                    if ($profileRequest) {
                        $profileRequest['profile'] = $this->_changeKeyFormat($profileRequest['profile']);
                        $profileRequest['profile'] = $this->_deleteNegativeValues($profileRequest['profile'], $methodDB);
                        $config[$country][$methodShop]['details'] = $profileRequest['profile'];
                        if ($methodShop == 'rate') {
                            $profileRequest['installment_configuration'] = $this->_changeKeyFormat($profileRequest['installment_configuration']);
                            $config[$country][$methodShop]['installment_configuration'] = $profileRequest['installment_configuration'];
                        }
                        if ($methodShop == 'elv' && $country == 'de') {
                            $config[$country][$methodShop]['iban_only'] = (bool) $settings->pi_ratepay_settings__iban_only->rawValue;
                        }
                    } else {
                        $errMsg[$country][$methodShop] = "PI_RATEPAY_PROFILE_ERROR_CREDENTIALS_INVALID_";
                        $errMsg[$country][$methodShop] .= ($config[$country][$methodShop]['sandbox']) ? "INT" : "LIVE";
                    }
                }

                if ($config[$country][$methodShop]['active'] === true || $country == 'de') {
                    $activeCountries[$country] = $country;
                }
            }
        }
        //die(print_r($config));
        $this->addTplParam('config', $config);

        if (count($errMsg) > 0) {
            $this->addTplParam('errMsg', $errMsg);
        }

        $this->addTplParam('allCountries', $countries);
        $this->addTplParam('activeCountries', $activeCountries);
        $this->addTplParam('allMethods', $methods);

        return "pi_ratepay_profile.tpl";

    }

    public function saveRatepayProfile()
    {
        $countries = pi_ratepay_util_utilities::$_RATEPAY_ALLOWED_COUNTRIES;
        $methods = pi_ratepay_util_utilities::$_RATEPAY_PAYMENT_METHOD_NAMES;

        $settings = oxNew('pi_ratepay_settings');
        $errMsg = array();

        foreach ($countries as $country) {

            foreach ($methods as $methodDB => $methodShop) {
                $shopId = $this->getConfig()->getShopId();
                $shopId = $settings->setShopIdToOne($shopId);
                $settings->loadByType($methodDB, $shopId, $country);

                $methodActive = (bool) $this->_isParameterCheckedOn(oxRegistry::getConfig()->getRequestParameter('rp_active_' . $methodShop . '_' . $country));
                $profileId = oxRegistry::getConfig()->getRequestParameter('rp_profile_id_' . $methodShop . '_' . $country);
                $securityCode = oxRegistry::getConfig()->getRequestParameter('rp_security_code_' . $methodShop . '_' . $country);

                $firstSaveArray = array(
                    'profile_id' => $profileId,
                    'security_code' => $securityCode,
                    'url' => pi_ratepay_util_Utilities::$_RATEPAY_PRIVACY_NOTICE_URL,
                    'sandbox' => $this->_isParameterCheckedOn(oxRegistry::getConfig()->getRequestParameter('rp_sandbox_' . $methodShop . '_' . $country)),
                    'logging' => $this->_isParameterCheckedOn(oxRegistry::getConfig()->getRequestParameter('rp_logging_' . $methodShop . '_' . $country)),
                    'whitelabel' => $this->_isParameterCheckedOn(oxRegistry::getConfig()->getRequestParameter('rp_whitelabel_' . $methodShop . '_' . $country)),
                );//'duedate' => (int) oxRegistry::getConfig()->getRequestParameter('rp_duedate_' . $methodShop . '_' . $country)


                if ($this->_checkOnShopId($shopId, $country, $methodDB) == false){
                    $insertSql = $this->_createInsertSql($shopId, $country, $methodDB);
                    $this->_insertSettings($insertSql);
                }
                $insertSql = $this->_createUpdateSql($firstSaveArray, $shopId, $country, $methodDB);
                $this->_insertSettings($insertSql);

                if (!empty($profileId) && !empty($securityCode)) {
                    $extendedData = array('profileId' => $profileId, 'securityCode' => $securityCode, 'country' => $country);
                    $profileRequest = $this->_callProfileRequest('pi_ratepay_' . $methodShop, $extendedData);
                } else {
                    $profileRequest = false;
                }

                if ($methodActive === true) {
                    if ($profileRequest) {
                        $methodActive = ((bool) $profileRequest['profile']['eligibility-ratepay-' . $methodDB] && (int) $profileRequest['profile']['activation-status-' . $methodDB] == 2);
                        if ($methodActive === false) {
                            $errMsg[$country][$methodShop] = "PI_RATEPAY_PROFILE_ERROR_DEACTIVATED_BY_REQUEST";
                        } elseif (!strstr($profileRequest['profile']['country-code-billing'], strtoupper($country))) {
                            $methodActive = false;
                            $errMsg[$country][$methodShop] = "PI_RATEPAY_PROFILE_ERROR_DEACTIVATED_BY_REQUEST";
                        }
                    } elseif (!empty($profileId) && !empty($securityCode)) {
                        $methodActive = false;
                        $errMsg[$country][$methodShop] = "PI_RATEPAY_PROFILE_ERROR_CREDENTIALS_INVALID_";
                        $errMsg[$country][$methodShop] .= ($firstSaveArray['sandbox']) ? "INT" : "LIVE";
                    } else {
                        $methodActive = false;
                    }
                } else {
                    $methodActive = false;
                }

                if ($profileRequest) {
                    $secondSaveArray = array(
                        'limit_min' => (int) $profileRequest['profile']['tx-limit-' . $methodDB . '-min'],
                        'limit_max' => (int) $profileRequest['profile']['tx-limit-' . $methodDB . '-max'],
                        'b2b' => $this->_isParameterCheckedYes($profileRequest['profile']['b2b-' . $methodDB]),
                        'ala' => $this->_isParameterCheckedYes($profileRequest['profile']['delivery-address-' . $methodDB]),
                        'dfp' => $this->_isParameterCheckedYes($profileRequest['profile']['eligibility-device-fingerprint']),
                        'dfp_snippet_id' => $profileRequest['profile']['device-fingerprint-snippet-id']
                    );

                    $insertSql = $this->_createUpdateSql($secondSaveArray, $shopId, $country, $methodDB);
                    $this->_insertSettings($insertSql);
                }

                $thirdSaveArray = array(
                    'active' => $methodActive
                );

                $insertSql = $this->_createUpdateSql($thirdSaveArray, $shopId, $country, $methodDB);
                $this->_insertSettings($insertSql);

                // save installment configuration
                if ($methodShop == 'rate' && $methodActive && $profileRequest) {
                    $installmentSaveArray = array(
                        'month_allowed' => "[" . $profileRequest['installment_configuration']['month-allowed'] . "]"
                    );

                    $insertSql = $this->_createUpdateSql($installmentSaveArray, $shopId, $country, $methodDB);
                    $this->_insertSettings($insertSql);
                }

                if ($methodShop == 'elv' && $country == 'de') {
                    $elvSaveArray = array(
                        'iban_only' => $this->_isParameterCheckedOn(oxRegistry::getConfig()->getRequestParameter('rp_iban_only_' . $methodShop . '_' . $country))
                    );

                    $insertSql = $this->_createUpdateSql($elvSaveArray, $shopId, $country, $methodDB);
                    $this->_insertSettings($insertSql);
                }
            }
        }

        $this->addTplParam('saved', true);
        $this->addTplParam('errMsg', $errMsg);
    }

    private function _callProfileRequest($method, $extendedData = array()) {
        $ratepayRequest = oxNew('pi_ratepay_ratepayrequest', $method, null, null, $extendedData);
        $profileRequestResult = $ratepayRequest->profileRequest($extendedData['country']);

        if ((string) $profileRequestResult['response']->head->processing->status->attributes()->code == "OK" && (string) $profileRequestResult['response']->head->processing->result->attributes()->code == "500") {
            return array(
                'profile' => (array) $profileRequestResult['response']->content->{'master-data'},
                'installment_configuration' => (array) $profileRequestResult['response']->content->{'installment-configuration-result'}
            );
        }

        return false;
    }

    private function _changeKeyFormat($arrayBefore) {
        foreach ($arrayBefore as $key => $value) {
            $newKey = ucwords(str_replace("-", " ", $key));
            $arrayAfter[$newKey] = $value;
        }
        return $arrayAfter;
    }

    private function _deleteNegativeValues($array, $method) {
        foreach ($array as $key => $value) {
            if (empty($value) || $value == "no" || !strstr(strtolower($key), $method)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    private function _createInsertSql($shopid, $country, $method){
        $insertSql = 'INSERT INTO ' . $this->_tableName . '(shopid, country, type) VALUES (' . $shopid . ', "' . strtoupper($country) . '", "' . $method . '")';
        return $insertSql;

    }

    private function _createUpdateSql($settingsArray, $shopid, $country, $method){
        $updateSql = 'UPDATE ' . $this->_tableName . ' SET';
        foreach ($settingsArray as $key => $value) {
          $updateSql .= ' ' . $key . ' = "' . $value . '",';
        }
        $updateSql = substr($updateSql, 0, -1); // Removing last comma

        $updateSql .= ' WHERE shopid = "' . $shopid . '" AND country = "' . $country . '" AND type = "' . $method . '"';

        return $updateSql;
    }

    private function _checkOnShopId($shopid, $country, $method){

        $oDb = oxDb::getDb();
        $sqlResult = $oDb->getAssoc('SELECT * FROM ' . $this->_tableName . ' WHERE shopid = "' . $shopid .'" AND country = "' . $country .'" AND type = "' . $method .'"');

        return count($sqlResult) > 0;
    }

    private function _insertSettings($insertSql){
        $oDb = oxDb::getDb();
        $oDb->Execute($insertSql);
    }
}
