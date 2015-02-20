[{if $sPaymentID == "pi_ratepay_elv"}]
[{assign var="dynvalue" value=$oView->getDynValue()}]
    [{if $pi_ratepay_elv_country == "DE"}]
        <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]/out/blocks/src/js/libs/ratepay_elv.js"></script>
    [{/if}]
<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] style="position:relative; [{if !$pi_ratepay_elv_whitelabel}]top:-18px;[{/if}]">
        <label for="payment_[{$sPaymentID}]"><b>
            [{if !$pi_ratepay_elv_whitelabel}]
                <img src="[{$oViewConf->getModuleUrl('pi_ratepay')}]out/blocks/img/pi_ratepay_elv_checkout_logo.png" title="RatePAY [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_WHITELABEL_TEXT"}]" alt="RatePAY [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_WHITELABEL_TEXT"}]" />
            [{else}]
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_WHITELABEL_TEXT"}]
            [{/if}]
        </b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <div id="policy[{$sPaymentID}]" style="display: none;">
            <p>
                <font style="font-weight: bold;">[{$oxcmp_shop->oxshops__oxname->value}]</font>
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_INFORMATION_TEXT_1"}]
                [{$pi_ratepay_elv_duedays}]
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_INFORMATION_TEXT_1_PART_2"}]
            </p>
            <p>
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_INFORMATION_TEXT_2"}]
                [{$pi_ratepay_elv_minimumAmount}]
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_INFORMATION_TEXT_3"}]
                [{$pi_ratepay_elv_maximumAmount}]
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_INFORMATION_TEXT_4"}]
            </p>
        </div>
        <button id="policyButton[{$sPaymentID}]" class="submitButton largeButton" type="button">
            <span class="policyButtonText[{$sPaymentID}]">[{oxmultilang ident="PI_RATEPAY_SHOW_MORE_INFORMATION"}]</span>
            <span class="policyButtonText[{$sPaymentID}]" style="display: none;">[{oxmultilang ident="PI_RATEPAY_HIDE_MORE_INFORMATION"}]</span>
        </button>
        <br/>
        <br/>
        <div style="border: 1px solid #BDBDBD; padding-left: 4px;">
            [{oxmultilang ident="PI_RATEPAY_VIEW_RATEPAY_ADDRESS"}]
            <br/>
            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_CREDITOR_ID_TEXT"}]: [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_CREDITOR_ID_VALUE"}]
            <br/>
            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_MANDATE_TEXT"}]: [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_MANDATE_VALUE"}]
        </div>
        <ul class="form">
			[{if isset($pi_ratepay_elv_fon_check)}]
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_FON"}]</label>
					<input name='pi_ratepay_elv_fon' type='text' value='' size='37'/>
				</li>
				<li>
					<label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_MOBILFON"}]</label>
					<input name='pi_ratepay_elv_mobilfon' type='text' value='' size='37'/>
					<div class='note'>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PAYMENT_FON_NOTE"}]</div>
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
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_OWNER"}]:</label>
                <input name='pi_ratepay_elv_bank_owner' maxlength='255' size='37' type='text' value='[{$piDbBankowner}]'/>
            </li>
            <li>
                <label>
                    [{if $pi_ratepay_elv_country == "DE"}][{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_ACCOUNT_NUMBER"}] /[{/if}]
                    [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_IBAN"}]:</label>
                <input name='pi_ratepay_elv_bank_account_number' maxlength='50' size='37' type='text' value='[{if $piDbBankiban}][{$piDbBankiban}][{else}][{$piDbBankaccountnumber}][{/if}]' onkeyup="rpElvSwitch(this);" onchange="rpElvSwitch(this);" />            </li>
            <li id="PI_RATEPAY_ELV_VIEW_BANK_CODE" style="display: [{if $piDbBankiban}]none[{else}]inline-block[{/if}]">
                <label>
                    [{if $pi_ratepay_elv_country == "DE"}][{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_CODE"}] /[{/if}]
                    [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_BIC"}]:</label>
                <input name='pi_ratepay_elv_bank_code' maxlength='255' size='37' type='text' value='[{$piDbBankcode}]'/>
            </li>
        </ul>
        <div style="margin: 15px 0;">
            <table>
                <tr>
                    <td>
                        <input type="checkbox" name="pi_ratepay_elv_privacy" value="1" style="float: left;" />
                    </td>
                    <td>
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_1"}]
                        [{oxmultilang ident="PI_RATEPAY_VIEW_RATEPAY_ADDRESS"}]
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_2"}]
                        <a href='[{$pi_ratepay_elv_ratepayurl}]' target='_blank' style="text-decoration:underline;">[{oxmultilang ident="PI_RATEPAY_VIEW_PRIVACY_AGREEMENT_PRIVACYPOLICY"}]</a>
                        [{if $policyurl != '' }]
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_3"}]
                        <a href='[{$policyurl}]' target='_blank' style="text-decoration:underline;">[{oxmultilang ident="PI_RATEPAY_VIEW_PRIVACY_AGREEMENT_OWNERPOLICY"}]</a>
                        [{/if}]
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_4"}]
                        <br/><br/>
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_5"}]
                        </br>
                        [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_6"}]
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
