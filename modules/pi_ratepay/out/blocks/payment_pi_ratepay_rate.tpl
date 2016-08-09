[{if $sPaymentID == "pi_ratepay_rate"}]
[{assign var="dynvalue" value=$oView->getDynValue()}]
<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] style="position:relative;">
        <label for="payment_[{$sPaymentID}]"><b>
                [{$paymentmethod->oxpayments__oxdesc->value}]
        </b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        [{if $pi_ratepay_rate_sandbox_notification == 1 }]
        <div id="sandbox_notification[{$sPaymentID}]" style="background: yellow; color: black; border: 3px dashed red; font-weight: bold; text-align: center; font-size:14px; padding-top: 10px; ">
            <p>
                [{oxmultilang ident="PI_RATEPAY_RATE_VIEW_SANDBOX_NOTIFICATION"}]
            </p>
        </div>
        [{/if}]
        </br>
        <ul class="form">
            [{if isset($pi_ratepay_rate_fon_check)}]
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_FON"}]</label>
                <input name='pi_ratepay_rate_fon' type='text' value='' size='37'>
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_MOBILFON"}]</label>
                <input name='pi_ratepay_rate_mobilfon' type='text' value='' size='37'>
                <div class='note'>[{ oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_FON_NOTE" }]</div>
            </li>
            [{/if}]
            [{if isset($pi_ratepay_rate_birthdate_check)}]
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE"}]</label>
                <input name='pi_ratepay_rate_birthdate_day' maxlength='2' type='text' value='' data-fieldsize='small'>
                <input name='pi_ratepay_rate_birthdate_month' maxlength='2' type='text' value='' data-fieldsize='small'>
                <input name='pi_ratepay_rate_birthdate_year' maxlength='4' type='text' value='' data-fieldsize='small'>
                <div class='note'>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_BIRTHDATE_FORMAT"}]</div>
            </li>
            [{/if}]
            [{if isset($pi_ratepay_rate_company_check)}]
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_COMPANY"}]</label>
                <input name='pi_ratepay_rate_company' maxlength='255' size='37' type='text' value=''>
            </li>
            [{/if}]
            [{if isset($pi_ratepay_rate_ust_check)}]
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_RATE_VIEW_PAYMENT_UST"}]</label>
                <input name='pi_ratepay_rate_ust' maxlength='255' size='37' type='text' value=''>
            </li>
            [{/if}]
            [{if $pi_ratepay_rate_activateelv == 1}]
            <li>
                <label for="piRpRadioWire">[{oxmultilang ident="PI_RATEPAY_VIEW_RADIO_PAYMENT_WIRE"}]</label>
                <input id="piRpRadioWire" type="radio" name="pi_rp_rate_pay_method" value="pi_ratepay_rate_radio_wire" checked >
            </li>
            <li>
                <label for="piRpRadioElv">[{oxmultilang ident="PI_RATEPAY_VIEW_RADIO_LABEL_ELV"}]</label>
                <input id="piRpRadioElv" type="radio" name="pi_rp_rate_pay_method" value="pi_ratepay_rate_radio_elv">
            </li>
            [{/if}]
        </ul>
        [{if $pi_ratepay_rate_activateelv == 1}]
        <ul id="pi_ratepay_rate_bank_box" class="form">
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_OWNER"}]:</label>
                <input name='pi_ratepay_rate_bank_owner' maxlength='255' size='37' type='text' value='[{$piDbBankowner}]'/>
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_ACCOUNT_NUMBER"}]:</label>
                <input name='pi_ratepay_rate_bank_account_number' maxlength='255' size='37' type='text' value='[{$piDbBankaccountnumber}]'/>
            </li>
            <li>
                <label>[{oxmultilang ident="PI_RATEPAY_ELV_VIEW_BANK_CODE"}]:</label>
                <input name='pi_ratepay_rate_bank_code' maxlength='255' size='37' type='text' value='[{$piDbBankcode}]'/>
            </li>
        </ul>
        [{/if}]
    </dd>
</dl>

[{oxscript add="piTogglePolicy('$sPaymentID');"}]
[{oxscript add="$('#pi_ratepay_rate_bank_box').hide();"}]
[{oxscript add="piShow('#piRpRadioElv', '#pi_ratepay_rate_bank_box');"}]
[{oxscript add="piHide('#piRpRadioWire', '#pi_ratepay_rate_bank_box');"}]

[{else}]
[{$smarty.block.parent}]
[{/if}]
