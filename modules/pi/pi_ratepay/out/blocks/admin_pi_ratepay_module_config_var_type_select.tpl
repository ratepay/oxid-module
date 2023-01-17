<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{if $module_var == 'sRPInstallmentSettlement' || $module_var == 'sRPAustriaInstallmentSettlement'}]
    [{assign var=aAvailableSettlementTypes value=$oView->piGetAvailableSettlementTypes($module_var)}]
    <select class="select" name="confselects[[{$module_var}]]" [{$readonly}]>
        [{foreach from=$var_constraints.$module_var item='_field'}]
            [{if $_field|in_array:$aAvailableSettlementTypes}]
                <option value="[{$_field|escape}]"  [{if ($confselects.$module_var==$_field)}]selected[{/if}]>[{oxmultilang ident="SHOP_MODULE_`$module_var`_`$_field`"}]</option>
            [{/if}]
        [{/foreach}]
    </select>
[{else}]
    [{$smarty.block.parent}]
[{/if}]