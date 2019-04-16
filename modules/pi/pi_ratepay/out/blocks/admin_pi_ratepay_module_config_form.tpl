[{if $oView->piIsRatepayModuleConfig()}]
    <div id="ratepay-config-connectiontest" style="margin-bottom: 10px;">
        <table style="border: 1px solid gray;padding: 2px;margin: 2px;" cellpadding="2" cellspacing="2">
            <tr>
                <th>&nbsp;</th>
                <th><img src="[{$oView->piGetFlagUrl('de')}]"></th>
                <th><img src="[{$oView->piGetFlagUrl('at')}]"></th>
                <th><img src="[{$oView->piGetFlagUrl('ch')}]"></th>
                <th><img src="[{$oView->piGetFlagUrl('nl')}]"></th>
            </tr>
            <tr>
                <td>
                    <strong>[{oxmultilang ident="PI_RATEPAY_CONFIGTEST_INVOICE"}]</strong>
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('invoice', 'de')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('invoice', 'at')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('invoice', 'ch')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('invoice', 'nl')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
            </tr>
            <tr>
                <td>
                    <strong>[{oxmultilang ident="PI_RATEPAY_CONFIGTEST_INSTALLMENT"}]</strong>
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('installment', 'de')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('installment', 'at')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    <span style="color:lightgray;">[{oxmultilang ident="PI_RATEPAY_UNAVAILABLE"}]</span>
                </td>
                <td style="border: 1px solid gray;">
                    <span style="color:lightgray;">[{oxmultilang ident="PI_RATEPAY_UNAVAILABLE"}]</span>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>[{oxmultilang ident="PI_RATEPAY_CONFIGTEST_ELV"}]</strong>
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('elv', 'de')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('elv', 'at')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
                <td style="border: 1px solid gray;">
                    <span style="color:lightgray;">[{oxmultilang ident="PI_RATEPAY_UNAVAILABLE"}]</span>
                </td>
                <td style="border: 1px solid gray;">
                    [{if $oView->piTestConnectionEstablished('elv', 'nl')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
            </tr>
        </table>
    </div>
[{/if}]
[{$smarty.block.parent}]