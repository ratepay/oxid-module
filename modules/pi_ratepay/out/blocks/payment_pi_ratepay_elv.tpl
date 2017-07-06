[{if $sPaymentID == "pi_ratepay_elv"}]
[{assign var="dynvalue" value=$oView->getDynValue()}]

[{if !$pi_ratepay_elv_iban_only}]
    <script type="text/javascript">
        function rpElvSwitch(type) {
            if(type == 'classic') {
                document.getElementById('pi_ratepay_elv_classic_bankdata').style.display = 'block';
                document.getElementById('pi_ratepay_elv_sepa_bankdata').style.display = 'none';
                document.getElementById('pi_ratepay_elv_bank_datatype').value = 'classic';

            } else {
                document.getElementById('pi_ratepay_elv_classic_bankdata').style.display = 'none';
                document.getElementById('pi_ratepay_elv_sepa_bankdata').style.display = 'block';
                document.getElementById('pi_ratepay_elv_bank_datatype').value = 'sepa';
            }
        }
        function showAgreement() {
            document.getElementById('pi_ratepay_elv_sepa_agreement').style.display = 'block';
            document.getElementById('pi_ratepay_elv_sepa_agreement_link').style.display = 'none';
        }
    </script>
    [{/if}]
<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" onclick="piCalculator();" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] style="position:relative;">
        <label for="payment_[{$sPaymentID}]"><b>
                [{$paymentmethod->oxpayments__oxdesc->value}]
        </b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        [{if $pi_ratepay_elv_sandbox_notification == 1 }]
        <div id="sandbox_notification[{$sPaymentID}]" style="background: yellow; color: black; border: 3px dashed red; font-weight: bold; text-align: center; font-size:14px; padding-top: 10px; ">
            <p>
                [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_SANDBOX_NOTIFICATION"}]
            </p>
        </div>
        [{/if}]
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
        </ul>

        [{if !$pi_ratepay_elv_iban_only}]
            <button id="policyButton[{$sPaymentID}]" class="submitButton largeButton" type="button" onclick="rpElvSwitch('sepa')">IBAN Kontodaten</button>
            <button id="policyButton[{$sPaymentID}]" class="submitButton largeButton" type="button" onclick="rpElvSwitch('classic')">Klassische Kontodaten</button>
        [{/if}]
        <input type="hidden" name="pi_ratepay_elv_bank_datatype" id="pi_ratepay_elv_bank_datatype" value="[{$pi_ratepay_elv_bank_datatype}]">
        <ul class="form" id="pi_ratepay_elv_sepa_bankdata" [{if $pi_ratepay_elv_bank_datatype=="classic"}] style="display: none" [{/if}]>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_OWNER"}]:</label>
                <label>[{$pi_ratepay_elv_bank_account_owner}]</label>
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_IBAN"}]:</label>
                <input name='pi_ratepay_elv_bank_iban' maxlength='50' size='37' type='text' value='[{$pi_ratepay_elv_bank_iban}]'/>
            </li>
        </ul>
        [{if !$pi_ratepay_elv_iban_only}]
        <ul class="form" id="pi_ratepay_elv_classic_bankdata" [{if $pi_ratepay_elv_bank_datatype=="sepa"}] style="display: none" [{/if}]>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_OWNER"}]:</label>
                <label>[{$pi_ratepay_elv_bank_account_owner}]</label>
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_ACCOUNT_NUMBER"}]:</label>
                <input name='pi_ratepay_elv_bank_account_number' maxlength='50' size='37' type='text' value='[{$pi_ratepay_elv_bank_account_number}]' />
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_CODE"}]:</label>
                <input name='pi_ratepay_elv_bank_code' maxlength='50' size='37' type='text' value='[{$pi_ratepay_elv_bank_code}]' />
            </li>
        </ul>
        [{/if}]
        <div style="margin: 15px 0;">
            <table>
                <tr>
                    <td>
                        <input type="hidden" name="pi_ratepay_elv_privacy" value="1" style="float: left;" />
                    </td>
                    <td>
                        <a id="pi_ratepay_elv_sepa_agreement_link" onclick="showAgreement();">[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT"}]</a>
                        <span id="pi_ratepay_elv_sepa_agreement" style="display: none">
                            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_1"}]
                            [{oxmultilang ident="PI_RATEPAY_VIEW_RATEPAY_ADDRESS"}]
                            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_2"}]
                            <a href='[{$pi_ratepay_elv_url}]' target='_blank' style="text-decoration:underline;">[{oxmultilang ident="PI_RATEPAY_VIEW_PRIVACY_AGREEMENT_PRIVACYPOLICY"}]</a>
                            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_4"}]
                            <br/><br/>
                            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_5"}]
                            </br>
                            [{oxmultilang ident="PI_RATEPAY_ELV_VIEW_PRIVACY_AGREEMENT_TEXT_6"}]
                        </span>
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
