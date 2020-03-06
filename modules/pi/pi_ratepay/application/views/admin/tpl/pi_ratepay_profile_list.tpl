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
            cl="pi_ratepay_profile_list"
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
                <col width="15%">
                <col width="7%">
            </colgroup>
            <tr class="listitem">
                <td valign="top" class="listfilter first" height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]profile_id]" value="[{$where.pi_ratepay_settings.profile_id}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter" height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]country]" value="[{$where.pi_ratepay_settings.country}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]sandbox]" value="[{$where.pi_ratepay_settings.sandbox}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]type]" value="[{$where.pi_ratepay_settings.type}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]limit_min]" value="[{$where.pi_ratepay_settings.limit_min}]">
                        </div>
                    </div>
                </td>
                <td valign="top" class="listfilter " height="20">
                    <div class="r1">
                        <div class="b1">
                            <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}][{$nameconcat}]limit_max]" value="[{$where.pi_ratepay_settings.limit_max}]">
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
                <td class="listheader first" height="15"><a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'profile_id', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_PROFILE_ID"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'country', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_COUNTRY"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'sandbox', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_SANDBOX"}]</a></td>
                <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'type', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_TYPE"}]</a></td>
                <td class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'limit_min', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT_MIN"}]</a></td>
                <td colspan="2" class="listheader" height="15">&nbsp;<a href="Javascript:top.oxid.admin.setSorting(document.search, '[{$listTable}]', 'limit_max', 'asc');document.search.submit();" class="listheader">[{oxmultilang ident="PI_RATEPAY_PROFILE_LIMIT_MAX"}]</a></td>
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
    
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__profile_id->value}]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__country->value}]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__sandbox->value}]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__type->value}]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__limit_min->value}]</a></div></td>
                    <td valign="top" class="[{$listclass}]"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{$listitem->pi_ratepay_settings__oxid->value}]');" class="[{$listclass}]">[{$listitem->pi_ratepay_settings__limit_max->value}]</a></div></td>
    
                    <td class="[{$listclass}]">
                        [{if !$readonly}]
                            [{if $listitem->blIsDerived && !$oViewConf->isMultiShop()}]
                                <a href="Javascript:top.oxid.admin.unassignThis('[{$listitem->pi_ratepay_settings__oxid->value }]');" class="unasign" id="una.[{$_cnt}]" [{include file="help.tpl" helpid=item_unassign}]></a>
                            [{/if}]
                            [{if !$readonly && !$listitem->blIsDerived}]
                                <a href="Javascript:top.oxid.admin.deleteThis('[{$listitem->pi_ratepay_settings__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
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