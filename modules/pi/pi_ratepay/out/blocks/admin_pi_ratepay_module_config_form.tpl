[{if $oView->piIsRatepayModuleConfig()}]
    <div id="ratepay-config-connectiontest">
        <table>
            <tr>
                <td>
                    [{oxmultilang ident="PI_RATEPAY_CONFIGTEST_INVOICE"}]
                </td>
                <td>
                    [{if $oView->piTestConnectionEstablished('pi_ratepay_rechnung')}]
                        <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                        <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
            </tr>
            <tr>
                <td>
                    [{oxmultilang ident="PI_RATEPAY_CONFIGTEST_INSTALLMENT"}]
                </td>
                <td>
                    [{if $oView->piTestConnectionEstablished('pi_ratepay_rate')}]
                    <span style="color:green;">[{oxmultilang ident="PI_RATEPAY_CONNECTED"}]</span>
                    [{else}]
                    <span style="color:red;">[{oxmultilang ident="PI_RATEPAY_DISCONNECTED"}]</span>
                    [{/if}]
                </td>
            </tr>
            <tr>
                <td>
                    [{oxmultilang ident="PI_RATEPAY_CONFIGTEST_ELV"}]
                </td>
                <td>
                    [{if $oView->piTestConnectionEstablished('pi_ratepay_elv')}]
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