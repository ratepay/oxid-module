<!--
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
* @package   PayIntelligent_RatePAY_Rechnung
* @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
* @license	http://www.gnu.org/licenses/  GNU General Public License 3
*-->
[{include file="headitem.tpl" titre="[ratepay]"}]

[{if $error == 0}]

<table>
    <tr>
        <td width="40%">[{ oxmultilang ident="PI_RATEPAY_PROFILE_MERCHANTNAME" }]:</td>
        <td>[{$profile_response.merchant_name.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_MERCHANTSTATUS" }]:</td>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_MERCHANTSTATUS_`$profile_response.merchant_status.value`"}]</td>
    </tr>    
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_SHOPNAME" }]:</td>
        <td>[{$profile_response.shop_name.value}]</td>
    </tr>    
    <tr>
        <td colspan="2" style="font-weight: bold;">[{ oxmultilang ident="PI_RATEPAY_PROFILE_INVOICE" }]:</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ELIGIBILITY" }]:</td>
        <td class="[{$profile_response.eligibility_ratepay_invoice.saved}]">[{$profile_response.eligibility_ratepay_invoice.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION" }]:</td>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION_`$profile_response.activation_status_invoice.value`"}]</td>
    </tr> 
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT" }]:</td>
        <td>[{$profile_response.tx_limit_invoice_min.value}] / [{$profile_response.tx_limit_invoice_max.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_DELIVERYADDRESS" }]:</td>
        <td class="[{$profile_response.delivery_address_elv.saved}]">[{$profile_response.delivery_address_invoice.value}]</td>
    </tr> 
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_B2B" }]:</td>
        <td class="[{$profile_response.b2b_invoice.saved}]">[{$profile_response.b2b_invoice.value}]</td>
    </tr>               
    <tr>
        <td colspan="2" style="font-weight: bold;">[{ oxmultilang ident="PI_RATEPAY_PROFILE_ELV" }]:</td>
    </tr>    
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ELIGIBILITY" }]:</td>
        <td>[{$profile_response.eligibility_ratepay_elv.value}]</td>
    </tr>     
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION" }]:</td>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION_`$profile_response.activation_status_elv.value`"}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT" }]:</td>
        <td>[{$profile_response.tx_limit_elv_min.value}] / [{$profile_response.tx_limit_elv_max.value}]</td>
    </tr> 
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_DELIVERYADDRESS" }]:</td>
        <td class="[{$profile_response.delivery_address_elv.saved}]">[{$profile_response.delivery_address_elv.value}]</td>
    </tr> 
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_B2B" }]:</td>
        <td class="[{$profile_response.b2b_elv.saved}]">[{$profile_response.b2b_elv.value}]</td>
    </tr>
    <!--
    <tr>
        <td colspan="2" style="font-weight: bold;">[{ oxmultilang ident="PI_RATEPAY_PROFILE_PREPAYMENT" }]:</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ELIGIBILITY" }]:</td>
        <td>[{$profile_response.eligibility_ratepay_prepayment.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION" }]:</td>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION_`$profile_response.activation_status_prepayment.value`"}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT" }]:</td>
        <td>[{$PROFILE_response.tx_limit_prepayment_min.value}] / [{$profile_response.tx_limit_prepayment_max.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_DELIVERYADDRESS" }]:</td>
        <td class="[{$profile_response.delivery_address_prepayment.saved}]">[{$profile_response.delivery_address_prepayment.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_B2B" }]:</td>
        <td class="[{$profile_response.b2b_prepayment.saved}]">[{$profile_response.b2b_prepayment.value}]</td>
    </tr>
    -->
    <tr>
        <td colspan="2" style="font-weight: bold;">[{ oxmultilang ident="PI_RATEPAY_PROFILE_INSTALLMENT" }]:</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ELIGIBILITY" }]:</td>
        <td>[{$profile_response.eligibility_ratepay_installment.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION" }]:</td>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATION_`$profile_response.activation_status_installment.value`"}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT" }]:</td>
        <td>[{$profile_response.tx_limit_installment_min.value}] / [{$profile_response.tx_limit_installment_max.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_DELIVERYADDRESS" }]:</td>
        <td class="[{$profile_response.delivery_address_installment.saved}]">[{$profile_response.delivery_address_installment.value}]</td>
    </tr>
    <tr>
        <td>[{ oxmultilang ident="PI_RATEPAY_PROFILE_B2B" }]:</td>
        <td class="[{$profile_response.b2b_installment.saved}]">[{$profile_response.b2b_installment.value}]</td>
    </tr>

    <style type="text/css">
        td.saved {
            color: #0B610B;
        }
        td.notsaved {
            color: #DF0101;
        }
    </style>

    <tr>
        <td colspan="2">
            <table style="margin: 10px; border: 1px solid black;">
                <tr>
                    <td colspan="2">
                        <b>Ratenkonfiguration</b>
                    </td>
                </tr>
                <tr>
                    <td>Interestrate Minimum:</td>
                    <td class="[{$configuration_response.interestrate_min.saved}]">[{$configuration_response.interestrate_min.value}]</td>
                </tr>
                <tr>
                    <td>Interestrate Default:</td>
                    <td class="[{$configuration_response.interestrate_default.saved}]">[{$configuration_response.interestrate_default.value}]</td>
                </tr>
                <tr>
                    <td>Interestrate Maximum:</td>
                    <td class="[{$configuration_response.interestrate_max.saved}]">[{$configuration_response.interestrate_max.value}]</td>
                </tr>
                <tr>
                    <td>Month Number Minimum:</td>
                    <td class="[{$configuration_response.month_number_min.saved}]">[{$configuration_response.month_number_min.value}]</td>
                </tr>
                <tr>
                    <td>Month Number Maximum:</td>
                    <td class="[{$configuration_response.month_number_max.saved}]">[{$configuration_response.month_number_max.value}]</td>
                </tr>
                <tr>
                    <td>Month Longrun:</td>
                    <td class="[{$configuration_response.month_longrun.saved}]">[{$configuration_response.month_longrun.value}]</td>
                </tr>
                <tr>
                    <td>Months Allowed:</td>
                    <td class="[{$configuration_response.month_allowed.saved}]">[{$configuration_response.month_allowed.value}]</td>
                </tr>
                <tr>
                    <td>Payment Firstday:</td>
                    <td class="[{$configuration_response.payment_firstday.saved}]">[{$configuration_response.payment_firstday.value}]</td>
                </tr>
                <tr>
                    <td>Payment Amount:</td>
                    <td class="[{$configuration_response.payment_amount.saved}]">[{$configuration_response.payment_amount.value}]</td>
                </tr>
                <tr>
                    <td>Payment Lastrate:</td>
                    <td class="[{$configuration_response.payment_lastrate.saved}]">[{$configuration_response.payment_lastrate.value}]</td>
                </tr>
                <tr>
                    <td>Rate Minimum Normal:</td>
                    <td class="[{$configuration_response.rate_min_normal.saved}]">[{$configuration_response.rate_min_normal.value}]</td>
                </tr>
                <tr>
                    <td>Rate Minimum Longrun:</td>
                    <td class="[{$configuration_response.rate_min_longrun.saved}]">[{$configuration_response.rate_min_longrun.value}]</td>
                </tr>
                <tr>
                    <td>Service Charge:</td>
                    <td class="[{$configuration_response.service_charge.saved}]">[{$configuration_response.service_charge.value}]</td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td colspan="2">
            <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
                [{ $shop->hiddensid }]
                <input type="hidden" name="cl" value="pi_ratepay_Profile">
                <input type="hidden" name="fnc" value="reloadRatepayProfile">
                <input type="hidden" name="stoken" value="[{ $stoken }]">

                <input type="submit" class="edittext" name="[{ oxmultilang ident="PI_RATEPAY_PROFILE_RELOAD" }]" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="PI_RATEPAY_PROFILE_RELOAD" }]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;">
            </form>
        </td>
    </tr>    
</table>

[{/if}]

[{include file="bottomitem.tpl"}]
