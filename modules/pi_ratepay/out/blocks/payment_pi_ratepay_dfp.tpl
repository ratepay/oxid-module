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