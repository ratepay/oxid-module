[{if isset($pi_ratepay_dfp_token)}]
    <script language="JavaScript" async>
        var di = {t:'[{$pi_ratepay_dfp_token}]',v:'[{$pi_ratepay_dfp_snippet_id}]',l:'Checkout'};
    </script>
    <script type="text/javascript" src="//d.ratepay.com/[{$pi_ratepay_dfp_snippet_id}]/di.js" async></script>
    <noscript><link rel="stylesheet" type="text/css" href="//d.ratepay.com/di.css?t=[{$pi_ratepay_dfp_token}]&v=[{$pi_ratepay_dfp_snippet_id}]&l=Checkout"></noscript>
    <object type="application/x-shockwave-flash" data="//d.ratepay.com/[{$pi_ratepay_dfp_snippet_id}]/c.swf" style="float: right; visibility: hidden; height: 0px; width: 0px;">
        <param name="movie" value="//d.ratepay.com/[{$pi_ratepay_dfp_snippet_id}]/c.swf" />
        <param name="flashvars" value="t=[{$pi_ratepay_dfp_token}]&v=[{$pi_ratepay_dfp_snippet_id}]&l=Checkout"/>
        <param name="AllowScriptAccess" value="always"/>
    </object>
[{/if}]

[{foreach from=$piRatepayErrors item=pierror}]

    [{if $pierror == "-300"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR" }]<a href="[{$pi_ratepay_elv_url}]" target="_blank">[{ oxmultilang ident="PI_RATEPAY_RATE_VIEW_POLICY_PRIVACYPOLICY" }]</a>.</div>
    [{/if}]

    <!-- Rechnung -->
    [{if $pierror == "-400"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR" }]<a href="[{$pi_ratepay_rechnung_url}]" target="_blank">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_VIEW_POLICY_PRIVACYPOLICY" }]</a>.</div>
    [{/if}]

    [{if $pierror == "-401"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR_BIRTH" }]</div>
    [{/if}]

    [{if $pierror == "-404"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR_PHONE" }]</div>
    [{/if}]

    [{if $pierror == "-405"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR_ADDRESS" }]</div>
    [{/if}]

    [{if $pierror == "-406"}]
    <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR_ZIP" }]</div>
    [{/if}]

    [{if $pierror == "-414"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RECHNUNG_ERROR_AGE" }]</div>
    [{/if}]

    <!-- Rate -->
    [{if $pierror == "-407"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR" }]<a href="[{$pi_ratepay_rate_url}]" target="_blank">[{ oxmultilang ident="PI_RATEPAY_RATE_VIEW_POLICY_PRIVACYPOLICY" }]</a>.</div>
    [{/if}]

    [{if $pierror == "-408"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR_BIRTH" }]</div>
    [{/if}]

    [{if $pierror == "-412"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR_ADDRESS" }]</div>
    [{/if}]

    [{if $pierror == "-413"}]
    <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR_ZIP" }]</div>
    [{/if}]

    [{if $pierror == "-415"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR_AGE" }]</div>
    [{/if}]

    [{if $pierror == "-460"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_RATE_ERROR_PHONE" }]</div>
    [{/if}]

    <!-- ELV -->
    [{if $pierror == "-500"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_OWNER" }]</div>
    [{/if}]
    [{if $pierror == "-501"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_ACCOUNT_NUMBER" }]</div>
    [{/if}]
    [{if $pierror == "-502"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_CODE" }]</div>
    [{/if}]
    [{if $pierror == "-504"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR" }]<a href="[{$pi_ratepay_elv_url}]" target="_blank">[{ oxmultilang ident="PI_RATEPAY_ELV_VIEW_POLICY_PRIVACYPOLICY" }]</a>.</div>
    [{/if}]

    [{if $pierror == "-505"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_BIRTH" }]</div>
    [{/if}]

    [{if $pierror == "-506"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_ADDRESS" }]</div>
    [{/if}]

    [{if $pierror == "-507"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_AGE" }]</div>
    [{/if}]

    [{if $pierror == "-508"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_PHONE" }]</div>
    [{/if}]

    [{if $pierror == "-509"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_BANKCODE_TO_SHORT" }]</div>
    [{/if}]

    [{if $pierror == "-511"}]
    <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ELV_ERROR_ZIP" }]</div>
    [{/if}]

    <!-- All -->
    [{if $pierror == "-416"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ERROR_COMPANY" }]</div>
    [{/if}]

    [{if $pierror == "-418"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ERROR_CONNECTION_TIMEOUT" }]</div>
    [{/if}]

    [{if $pierror == "-419"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ERROR_BIRTHDAY_YEAR_DIGITS" }]</div>
    [{/if}]

    [{if $pierror == "-835"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ERROR_COMPANY_BIRTHDAY_DIGITS" }]</div>
    [{/if}]

    [{if $pierror == "-461"}]
        <div class="status error">[{ oxmultilang ident="PI_RATEPAY_ERROR_PRIVACY_AGREEMENT" }]</div>
    [{/if}]

    [{if $pierror == "-001"}]
        <div class="status error">[{$customer_message}]</div>
    [{/if}]
[{/foreach}]

<script type="text/javascript">
function piTogglePolicy(policy) {
    $('#policyButton' + policy).click(function() {
        $('.policyButtonText' + policy).toggle();
        $('#policy' + policy).toggle();
    });
}

function piShow(input, elementToToggle) {
    $(input).click(function() {
        $(elementToToggle).show();
    });
}

function piHide(input, elementToToggle) {
    $(input).click(function() {
        $(elementToToggle).hide();
    });
}
function piCalculator() {
    if ($('#payment_pi_ratepay_rate').is(':checked')) {
        $('#paymentNextStepBottom').prop('disabled', true);
    } else {
        $('#paymentNextStepBottom').prop('disabled', false);
    }

}
</script>
