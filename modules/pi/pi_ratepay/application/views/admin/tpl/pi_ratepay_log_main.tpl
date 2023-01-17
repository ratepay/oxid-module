<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
    function fcConfirmationBeforeAction(submitId, submitFunction) {
        var affectedForm = document.getElementById(submitId);
        var confirmDialog1 = affectedForm.confirm1.value;
        var confirmDialog2 = affectedForm.confirm2.value;
        var daysSelected = affectedForm.logdays.value;
        var confirmDialogFinal = confirmDialog1 + daysSelected + confirmDialog2;

        var answer = confirm(confirmDialogFinal);

        if (answer) {
            affectedForm.fnc.value= submitFunction;
            affectedForm.submit();
        }
    }
</script>

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="pi_ratepay_log_main">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

[{if $edit}]
<table cellspacing="0" cellpadding="0" border="0" width="98%">
    <colgroup><col width="20%"><col width="5%"><col width="75%"></colgroup>
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="pi_ratepay_log_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <tr>
        <td valign="top" class="edittext">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_ORDERNUMBER"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__order_number}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_TRANSACTIONID"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__transaction_id}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_PAYMENTMETHOD"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__payment_method}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_PAYMENTTYPE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__payment_type}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_PAYMENTSUBTYPE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__payment_subtype}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_RESULT"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__result}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_DATE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__date}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_RESULT_CODE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__result_code}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_REFERENCE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__reference}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_FIRST_NAME"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__first_name}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_LAST_NAME"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_logs__last_name}]
                    </td>
                </tr>
            </table>
        </td>
        <td></td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext vr" align="left">
            <table>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_REQUEST"}]&nbsp;
                    </td>
                    <td class="edittext">
                        <pre>[{$edit->getFormattedRequest()}]</pre>
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_LOGGING_RESPONSE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        <pre>[{$edit->getFormattedResponse()}]</pre>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form id="deleteForm" action="[{$oViewConf->getSelfLink()}]" method="post">
                            [{$oViewConf->getHiddenSid()}]
                            <input type="hidden" name="cl" value="pi_ratepay_log_main">
                            <input type="hidden" name="fnc" value="">
                            <input type="hidden" name="confirm1" value="[{oxmultilang ident="PI_RATEPAY_LOGGING_DELETE_CONFIRM"}]">
                            <input type="hidden" name="confirm2" value="[{oxmultilang ident="PI_RATEPAY_LOGGING_DELETE_CONFIRM2"}]">
                            [{oxmultilang ident="PI_RATEPAY_LOGGING_TEXTDAYS"}]<input type="text" name="logdays" maxlength="2" size="2" value="0"/> [{oxmultilang ident="PI_RATEPAY_LOGGING_DAYS"}].
                            <input type="button" name="delete" value="[{oxmultilang ident="PI_RATEPAY_LOGGING_DELETE"}]" onclick="fcConfirmationBeforeAction('deleteForm','deleteLogs')">
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
[{/if}]

[{include file="bottomitem.tpl"}]