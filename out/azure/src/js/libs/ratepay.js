function rpElvSwitch(element) {
    element.value = element.value.replace(/\s/g, "");
    if(isNaN(element.value)) {
        document.getElementById('PI_RATEPAY_ELV_VIEW_BANK_CODE').style.display = 'none';
    } else {
        document.getElementById('PI_RATEPAY_ELV_VIEW_BANK_CODE').style.display = 'inline-block';
    }
}