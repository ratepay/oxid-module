<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{if $sPaymentID == "pi_ratepay_elv"}]
[{assign var="dynvalue" value=$oView->getDynValue()}]

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(){
        checkElvForm();
    }, false);
</script>
<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" onclick="piCalculator();checkElvForm();" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] style="position:relative;">
        <label for="payment_[{$sPaymentID}]"><b>
                [{$paymentmethod->oxpayments__oxdesc->value}]
        </b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]" [{if $oView->getCheckedPaymentId() != $paymentmethod->oxpayments__oxid->value}]style="display:none"[{/if}]>
        [{if $pi_ratepay_elv_sandbox_notification == 1 }]
        <div id="sandbox_notification[{$sPaymentID}]" style="background: yellow; color: black; border: 3px dashed red; font-weight: bold; text-align: center; font-size:14px; padding-top: 10px; ">
            <p>
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_SANDBOX_NOTIFICATION"}]
            </p>
        </div>
        [{/if}]
        <div>
            [{ oxmultilang ident="PI_RATEPAY_POLICY" }]
        </div>
        <br/>
        <div style="padding-left: 4px;">
            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_MAND_CRED_NOTIFICATION"}]
        </div>
        <ul class="form">
			[{if isset($pi_ratepay_elv_fon_check)}]
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_FON"}]</label>
					<input name='pi_ratepay_elv_fon' type='text' value='' size='37'/>
				</li>
			[{/if}]
			[{if isset($pi_ratepay_elv_birthdate_check)}]
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE"}]</label>
					<input name='pi_ratepay_elv_birthdate_day' maxlength='2' type='text' value='' data-fieldsize='small'/>
					<input name='pi_ratepay_elv_birthdate_month' maxlength='2' type='text' value='' data-fieldsize='small'/>
					<input name='pi_ratepay_elv_birthdate_year' maxlength='4' type='text' value='' data-fieldsize='small'/>
					<div class='note'>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_BIRTHDATE_FORMAT"}]</div>
				</li>
			[{/if}]
			[{if isset($pi_ratepay_elv_company_check)}]
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_COMPANY"}]</label>
					<input name='pi_ratepay_elv_company' maxlength='255' size='37' type='text' value=''/>
				</li>
			[{/if}]
			[{if isset($pi_ratepay_elv_ust_check)}]
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_UST"}]</label>
					<input name='pi_ratepay_elv_ust' maxlength='255' size='37' type='text' value=''/>
				</li>
			[{/if}]
        </ul>

        <input type="hidden" name="pi_ratepay_elv_bank_datatype" id="pi_ratepay_elv_bank_datatype" value="[{$pi_ratepay_elv_bank_datatype}]">
        <div class="form" id="pi_ratepay_elv_sepa_bankdata" [{if $pi_ratepay_elv_bank_datatype=="classic"}] style="display: none" [{/if}]>
            <p style="margin-bottom: 15px;">
                <label class="">[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_OWNER"}]</label>
                [{if !empty($pi_ratepay_elv_company_name)}]
                <select name="rp_sepa_use_company_name">
                    <option selected="selected" value="1">[{$pi_ratepay_elv_company_name}]</option>
                    <option value="0">[{$pi_ratepay_elv_bank_account_owner}]</option>
                </select>
                [{else}]
                    <input type="text" class="form-control disabled" value="[{$pi_ratepay_elv_bank_account_owner}]" disabled />
                    <input type="hidden" name="rp_sepa_use_company_name" value="0" />
                [{/if}]
            </p>
            <p>
                <label class="">[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_IBAN"}]</label>
                <input id="pi_ratepay_elv_bank_iban" name="pi_ratepay_elv_bank_iban" onchange="checkElvForm()" maxlength='50' size='37' type="text" value="[{$pi_ratepay_elv_bank_iban}]" class="form-control required" />
            </p>
        </div>

        <div style="margin: 15px 0;">
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="pi_ratepay_elv_privacy" value="1" style="float: left;" />
                    </td>
                    <td>
                        <div id="pi_ratepay_elv_sepa_agreement">
                            <p>
                                <input type="checkbox" name="pi_ratepay_elv_sepa_agreement_check" id="pi_ratepay_elv_sepa_agreement_check" onchange="checkElvForm()" class="required" />
                                <span style="margin-left: 5px">[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_1"}]</span>
                            </p>
                            <p>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_2"}]</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </dd>
</dl>

[{oxscript add="piTogglePolicy('$sPaymentID');"}]

[{else}]
[{$smarty.block.parent}]
[{/if}]
