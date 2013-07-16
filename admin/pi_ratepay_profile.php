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

    public function render()
    {
        parent::render();

        $ratepayRequest = oxNew('pi_ratepay_ratepayrequest', 'pi_ratepay_rate');
        $profileRequestResult = $ratepayRequest->profileRequest();

        $db_rateconfig = new oxBase();
        $db_rateconfig->init('pi_ratepay_settings');

        $response_PR = (array) $profileRequestResult['response']->content->{'master-data'};
        //print_r($response_PR);

        /*foreach($this->_getListOfPaymentMethods() as $method_eng => $method_de) {
            $selectQuery = $db_rateconfig->buildSelectString(array($db_rateconfig->getViewName() . ".type" => strtolower($method_eng)));
            $db_rateconfig->_isLoaded = $db_rateconfig->assignRecord($selectQuery);

            $profile_response['delivery_address_'.$method_eng]['value'] = $response_PR['delivery-address-'.$method_eng];
            $response_PR['delivery-address-'.$method_eng] = ($response_PR['delivery-address-'.$method_eng] == "yes") ? 1 : 0;
            $profile_response['delivery_address_'.$method_eng]['saved'] = ($response_PR['delivery-address-'.$method_eng] == $db_rateconfig->pi_ratepay_settings__delivery_address->rawValue) ? "saved" : "notsaved";
            //echo 'delivery-address-'.$method_eng." -> ".$response_PR['delivery-address-'.$method_eng]."=".$db_rateconfig->pi_ratepay_settings__delivery_address->rawValue."<br>";

            $profile_response['b2b_'.$method_eng]['value'] = $response_PR['b2b-'.$method_eng];
            $response_PR['b2b-'.$method_eng] = ($response_PR['b2b-'.$method_eng] == "yes") ? 1 : 0;
            $profile_response['b2b_'.$method_eng]['saved'] = ($response_PR['b2b_'.$method_eng] == $db_rateconfig->pi_ratepay_settings__b2b->rawValue) ? "saved" : "notsaved";
            echo '<br>b2b-'.$method_eng." -> ".$response_PR['b2b-'.$method_eng]."=".$db_rateconfig->pi_ratepay_settings__b2b->rawValue;
        }*/

        foreach ((array) $profileRequestResult['response']->content->{'master-data'} as $field => $value) {
            $fieldName = str_replace("-", "_", $field);
            $profile_response[$fieldName]['value'] = $value;
        }

        $db_rateconfig = new oxBase();
        $db_rateconfig->init('pi_ratepay_rate_configuration');
        $db_rateconfig->load('1');

        foreach ((array) (array) $profileRequestResult['response']->content->{'installment-configuration-result'} as $field => $value) {
            $fieldName = str_replace("-", "_", $field);
            $configuration_response[$fieldName]['value'] = $value;
            $dbFieldName = "pi_ratepay_rate_configuration__".$fieldName;
            //$configuration_response[$fieldName]['saved'] = ($db_rateconfig->$dbFieldName->rawValue == $value) ? "saved" : "notsaved";
        }

        if (!empty($profile_response)) {
            $this->addTplParam('error', false);
            $this->addTplParam('profile_response', $profile_response);
            $this->addTplParam('configuration_response', $configuration_response);
        } else {
            $this->addTplParam('error', true);
        }

        //print_r($response_CR);

        return "pi_ratepay_profile.tpl";
    }

    public function reloadRatepayProfile()
    {
        $ratepayRequest = oxNew('pi_ratepay_ratepayrequest', 'pi_ratepay_rate');
        $profileRequestResult = $ratepayRequest->profileRequest();

        $response_PR = $profileRequestResult['response']->content->{'master-data'};
        $response_CR = $profileRequestResult['response']->content->{'installment-configuration-result'};

        foreach($this->_getListOfPaymentMethods() AS $paymentMethod => $paymentMethodDE) {
            // Saving activity and amount limits in oxpayments
            $tbl = new oxBase();
            $tbl->init('oxpayments');
            $tbl->load('pi_ratepay_' . $paymentMethodDE);

            if($tbl->{'_sOXID'}) {
                $oxpayments['OXACTIVE'] = ( $response_PR->{'eligibility-ratepay-' . $paymentMethod} == 'yes' && $response_PR->{'activation-status-' . $paymentMethod} == '2' ) ? '1' : '0';
                $oxpayments['OXFROMAMOUNT'] = $response_PR->{'tx-limit-' . $paymentMethod . '-min'};
                $oxpayments['OXTOAMOUNT'] = $response_PR->{'tx-limit-' . $paymentMethod . '-max'};

                $tbl->assign($oxpayments);

                $tbl->save();
            }

            // Saving b2b and delivery address in pi_ratepay_settings
            $tbl = new oxBase();
            $tbl->init('pi_ratepay_settings');
            
            $selectQuery = $tbl->buildSelectString(array($tbl->getViewName() . ".type" => strtolower($paymentMethod)));

            $tbl->_isLoaded = $tbl->assignRecord($selectQuery);

            if($tbl->{'_sOXID'}) {
                $rpaysettings['B2B'] = ($response_PR->{'b2b-' . $paymentMethod} == "yes") ? "1" : "0";
                $rpaysettings['DELIVERY_ADDRESS'] = ($response_PR->{'delivery-address-' . $paymentMethod} == "yes") ? "1" : "0";

                $tbl->assign($rpaysettings);

                $tbl->save();
            }             
        }

        // Saving installment configuration in pi_ratepay_rate_configuration
        $tbl = new oxBase();
        $tbl->init('pi_ratepay_rate_configuration');

        $selectQuery = $tbl->buildSelectString(array($tbl->getViewName() . ".OXID" => 1));

        $tbl->_isLoaded = $tbl->assignRecord($selectQuery);

        foreach((array) $response_CR AS $field => $value) {
            $dbFieldName = str_replace("-", "_", strtoupper($field));

            $rateconfig[$dbFieldName] = $value;

        }

        $rateconfig['DATE']                                = date("Y-m-d H:i:s");

        //print_r((array) $response_CR);
        //print_r($rateconfig);

        $tbl->assign($rateconfig);

        $tbl->save();
    }    

    private function _getListOfPaymentMethods() {
        return
            array(
                'invoice' => "rechnung",
                'installment' => "rate",
                'elv' => "elv"
            );
        // 'prepayment' => "vorkasse"
    }
}
