<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{$smarty.block.parent}]
[{if $pi_payment->getId() == "pi_ratepay_rate0"}]
    <link type="text/css" rel="stylesheet" href="modules/pi/pi_ratepay/installment/css/style.css"/>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/path.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/layout.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/ajax.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/mouseaction.js"></script>
    <div id="pi_ratepay_rate0_pirpmain-cont">

    </div>
    <script type="text/javascript">
    if(document.getElementById('pi_ratepay_rate0_pirpmain-cont')) {
        piLoadrateResult('pi_ratepay_rate0');
        if (document.getElementsByClassName('rp-table-striped').length > 0) {
            document.getElementsByClassName('rp-table-striped')[0].style.width = '100%';
        }
    }
    </script>
[{/if}]
