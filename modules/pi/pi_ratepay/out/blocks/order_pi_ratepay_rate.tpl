[{$smarty.block.parent}]
[{if $pi_payment->getId() == "pi_ratepay_rate"}]
    <link type="text/css" rel="stylesheet" href="modules/pi/pi_ratepay/installment/css/style.css"/>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/path.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/layout.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/ajax.js"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('pi_ratepay')}]installment/js/mouseaction.js"></script>
    <div id="pirpmain-cont">

    </div>
    <script type="text/javascript">
    if(document.getElementById('pirpmain-cont')) {
        piLoadrateResult();
    }
    </script>
[{/if}]
