[{$smarty.block.parent}]
[{if $edit->oxorder__ratepaycreditamount->value > 0}]
    <br>
    <b>[{oxmultilang ident="PI_RATEPAY_RATEPAY"}]: </b><br><br>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="edittext" height="15" style="width: 143px;">[{oxmultilang ident="PI_RATEPAY_CREDIT"}]</td>
            <td class="edittext" align="right"><b>[{$edit->ratepayGetFormattedCreditAmount()}]</b></td>
            <td class="edittext">&nbsp;<b>[{if $edit->oxorder__oxcurrency->value}] [{$edit->oxorder__oxcurrency->value}] [{else}] [{$currency->name}] [{/if}]</b></td>
        </tr>
    </table>
[{/if}]