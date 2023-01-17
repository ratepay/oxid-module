<!--
*
* Copyright (c) Ratepay GmbH
*
*For the full copyright and license information, please view the LICENSE
*file that was distributed with this source code.
*-->
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ oxmultilang ident="PI_RATEPAY_NODETAILS" }]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="pi_ratepay_details">
</form>

[{include file="bottomitem.tpl"}]