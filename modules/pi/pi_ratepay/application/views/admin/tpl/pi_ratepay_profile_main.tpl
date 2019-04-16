[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="pi_ratepay_profile_main">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<table cellspacing="0" cellpadding="0" border="0" width="98%">
    <colgroup><col width="20%"><col width="5%"><col width="75%"></colgroup>
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="pi_ratepay_profile_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <tr>
        <td valign="top" class="edittext">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_SHOPID"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__shopid->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__active->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_COUNTRY"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__country->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_PROFILE_ID"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__profile_id->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_URL"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__url->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_SANDBOX"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__sandbox->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_TYPE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__type->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT_MIN"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__result_limit_min->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT_MAX"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__limit_max->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT_MAX_B2B"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__limit_max_b2b->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_MONTH_ALLOWED"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__month_allowed->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_MIN_RATE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__min_rate->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_INTEREST_RATE"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__interest_rate->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_PAYMENT_FIRSTDAY"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__payment_firstday->value}]
                    </td>
                </tr>
            </table>
        </td>
        <td></td>
        <!-- Anfang rechte Seite -->
        <td valign="top" class="edittext vr" align="left">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_SAVEBANKDATA"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__savebankdata->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_ACTIVATE_ELV"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__activate_elv->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_B2B"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__b2b->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_ALA"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__ala->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_IBAN_ONLY"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__iban_only->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_DFP"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__dfp->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_DFP_SNIPPET_ID"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__dfp_snippet_id->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_CURRENCIES"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__currencies->value}]
                    </td>
                </tr>
                <tr>
                    <td class="edittext">
                        [{oxmultilang ident="PI_RATEPAY_PROFILE_DELIVERY_COUNTRIES"}]&nbsp;
                    </td>
                    <td class="edittext">
                        [{$edit->pi_ratepay_settings__delivery_countries->value}]
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

[{include file="bottomitem.tpl"}]