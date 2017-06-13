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

    if (mode == 'rate') {
        calcValue = document.getElementById('rp-rate-value').value;
        calcMethod = 'calculation-by-rate';
         if(document.getElementById('debitSelect')){
             dueDate = document.getElementById('debitSelect').value;
        } else {
            dueDate= '';
        }
    } else if (mode == 'runtime') {
        calcValue = month;
        calcMethod = 'calculation-by-time';
        if(document.getElementById('debitSelectRuntime')){
             dueDate = document.getElementById('debitSelectRuntime').value;
        } else {
            dueDate= '';
        }
    }

    xmlhttp.open("POST", pi_ratepay_rate_calc_path + "php/PiRatepayRateCalcRequest.php", false);

    xmlhttp.setRequestHeader("Content-Type",
        "application/x-www-form-urlencoded");

    xmlhttp.send("calcValue=" + calcValue + "&calcMethod=" + calcMethod + "&dueDate=" + dueDate + "&stoken=" + stoken + "&shp=" + shop);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById('piRpResultContainer').innerHTML = html;
        document.getElementById('piRpResultContainer').style.display = 'block';

    }
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
