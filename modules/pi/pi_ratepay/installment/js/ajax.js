/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
 */

function piRatepayRateCalculatorAction(mode, month) {
    var calcValue;
    var calcMethod;
    var paymentFirstday = 28;
    var html;

    document.getElementById('month').value = month;
    document.getElementById('mode').value = mode;
    document.getElementById('paymentNextStepBottom').disabled = false;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    stoken = document.getElementsByName("stoken")[0].value;
    if(document.getElementsByName("shp")[0] === undefined){
        shop = 1;
    } else{
        shop = document.getElementsByName("shp")[0].value;
    }

    if (document.getElementById('pi_ratepay_rate_bank_iban') !== null) {
        if (document.getElementById('pi_ratepay_rate_bank_iban').style.display !== 'none') {
            document.getElementById('rp-rate-elv').style.display = 'block';
            document.getElementById('paymentNextStepBottom').disabled = true;

            if (document.getElementById('pi_ratepay_rate_bank_iban').style.display === 'block'
                && document.getElementById('pi_ratepay_rate_bank_iban').value !== ''
                && document.getElementById('rp-sepa-aggreement').checked === true
            ) {
                document.getElementById('paymentNextStepBottom').disabled = false;
            }

            var bankAccount;
            if (document.getElementById('pi_ratepay_rate_bank_iban').value !== '') {
                if (document.getElementById('pi_ratepay_rate_bank_iban').style.display === 'block') {
                    bankAccount = document.getElementById('pi_ratepay_rate_bank_iban').value;
                }
            }
            paymentFirstday = document.getElementById('paymentFirstday').value;
        }
    } else {
        document.getElementById('paymentNextStepBottom').disabled = false;
    }

    if (mode == 'rate') {
        calcValue = document.getElementById('rp-rate-value').value;
        calcMethod = 'calculation-by-rate';

    } else if (mode == 'runtime') {
        calcValue = month;
        calcMethod = 'calculation-by-time';
    }

    xmlhttp.open("POST", pi_ratepay_rate_calc_path + "php/PiRatepayRateCalcRequest.php", false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send("calcValue=" + calcValue + "&calcMethod=" + calcMethod + "&bankAccount=" + bankAccount + "&paymentFirstday=" + paymentFirstday + "&stoken=" + stoken + "&shp=" + shop);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('piRpResultContainer').innerHTML = html;
        document.getElementById('piRpResultContainer').style.display = 'block';

    }
}

function updateCalculator() {
    var month = document.getElementById('month').value;
    var mode = document.getElementById('mode').value;

    if (month !== '') {
        piRatepayRateCalculatorAction(mode, month);
    }

}

function rp_change_payment(payment) {
    if (payment == 28) {
        document.getElementById('pi_ratepay_rate_bank_iban').value = '';
        document.getElementById('pi_ratepay_rate_bank_iban').style.display = 'none';
        document.getElementById('rp-rate-elv').style.display = 'none';
        document.getElementById('rp-switch-payment-type-direct-debit').style.display = 'block';
        document.getElementById('paymentFirstday').value = 2;
    } else {
        document.getElementById('pi_ratepay_rate_bank_iban').style.display = 'block';
        document.getElementById('rp-rate-elv').style.display = 'block';
        document.getElementById('rp-switch-payment-type-direct-debit').style.display = 'none';
        document.getElementById('paymentFirstday').value = 28;
    }
    updateCalculator();
}

function piLoadrateCalculator() {
    var html;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    stoken = document.getElementsByName("stoken")[0].value;
    if(document.getElementsByName("shp")[0] === undefined){
        shop = 1;
    } else{
        shop = document.getElementsByName("shp")[0].value;
    }

    xmlhttp.open("POST", pi_ratepay_rate_calc_path + "php/PiRatepayRateCalcDesign.php", false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send("stoken=" + stoken + "&shp=" + shop);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('pirpmain-cont').innerHTML = html;
    }
}

function piLoadrateResult() {
    var html;

    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    stoken = document.getElementsByName("stoken")[0].value;
    if(document.getElementsByName("shp")[0] === undefined){
        shop = 1;
    } else{
        shop = document.getElementsByName("shp")[0].value;
    }
    
    xmlhttp.open("POST", pi_ratepay_rate_calc_path + "php/PiRatepayRateCalcRequest.php", false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send("stoken=" + stoken + "&shp=" + shop);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('pirpmain-cont').innerHTML = html;
    }
}
