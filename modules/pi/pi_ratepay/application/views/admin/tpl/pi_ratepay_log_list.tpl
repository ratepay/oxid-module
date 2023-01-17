<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{include file="headitem.tpl" title="ADMINLINKS_LIST_TITLE"|oxmultilangassign box="list"}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
    [{/if}]

<script type="text/javascript">
    <!--
    window.onload = function ()
    {
        top.reloadEditFrame();
        [{if $updatelist == 1}]
            top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
    }
    //-->
</script>

<div id="liste">


    <form name="search" id="search" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{include
            file="_formparams.tpl"
            cl="pi_ratepay_log_list"
            lstrt=$lstrt actedit=$actedit oxid=$oxid
            fnc=""
            language=$actlang
            editlanguage=$actlang
        }]
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <colgroup>
                <col width="8%">
                <col width="15%">
                <col width="20%">
                <col width="20%">
                <col width="15%">
                <col width="10%">
                <col width="10%">
		<col width="10%">
                <col width="7%">
            </colgroup>
            <tr class="listitem">
                <td valign="top" class="listfilter first" height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]order_number]" value="[{$where.pi_ratepay_logs.order_number}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]transaction_id]" value="[{$where.pi_ratepay_logs.transaction_id}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]payment_method]" value="[{$where.pi_ratepay_logs.payment_method}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]payment_type]" value="[{$where.pi_ratepay_logs.payment_type}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]result]" value="[{$where.pi_ratepay_logs.result}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]reason]" value="[{$where.pi_ratepay_logs.reason}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]status]" value="[{$where.pi_ratepay_logs.status}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]date]" value="[{$where.pi_ratepay_logs.date}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter">
                    <div class="r1">
                        <div class="b1">
                            <div class="find">
                                <input class="listedit" type="submit" name="submitit" value="[{oxmultilang ident="GENERAL_SEARCH"}]">
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="listheader first" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'order_number', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_ORDERNUMBER"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'transaction_id', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_TRANSACTIONID"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'payment_method', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_PAYMENTMETHOD"}]</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'payment_type', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_PAYMENTTYPE"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'result', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_RESULT"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'reason', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_REASON"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'status', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_STATUS"}]</a></td>
                <td colspan="2" class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting( document.search, '[{$listTable}]', 'date', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_LOGGING_DATE"}]</a></td>
            </tr>
            [{assign var="blWhite" value=""}]
            [{assign var="_cnt" value=0}]
            [{foreach from=$mylist item=listitem}]
                [{assign var="_cnt" value=$_cnt+1}]
                <tr id="row.[{$_cnt}]">

                    [{if $listitem->blacklist == 1}]
                        [{assign var="listclass" value=listitem3}]
                    [{else}]
                        [{assign var="listclass" value=listitem$blWhite}]
                    [{/if}]
                    [{if $listitem->getId() == $oxid}]
                        [{assign var="listclass" value=listitem4}]
                    [{/if}]

                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__order_number->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__transaction_id->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__payment_method->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__payment_type->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__result->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__reason->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__status->value }]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_logs__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_logs__date->value }]</a></div></td>

                    <td class="[{$listclass}]">
                        [{if !$readonly}]
                            [{if $listitem->blIsDerived && !$oViewConf->isMultiShop()}]
                                <a href="Javascript:top.oxid.admin.unassignThis('[{$listitem->pi_ratepay_logs__oxid->value }]');" class="unasign" id="una.[{$_cnt}]" [{include file="help.tpl" helpid=item_unassign}]></a>
                            [{/if}]
                            [{if !$readonly && !$listitem->blIsDerived}]
                                <a href="Javascript:top.oxid.admin.deleteThis('[{$listitem->pi_ratepay_logs__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
                            [{/if}]
                        [{/if}]
                    </td>
                </tr>
                [{if $blWhite == "2"}]
                    [{assign var="blWhite" value=""}]
                [{else}]
                    [{assign var="blWhite" value="2"}]
                [{/if}]
            [{/foreach}]
            [{include file="pagenavisnippet.tpl" colspan="6"}]
        </table>
    </form>
</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="PI_RATEPAY_RATEPAY" }]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="PI_RATEPAY_LOGGING" }]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>
</body>
</html>