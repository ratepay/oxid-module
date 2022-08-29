
/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

function changeDetails(paymentMethod) {

    if (document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display === 'none') {
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-hide-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-no-details').style.display = 'block';
    } else {
        document.getElementById(paymentMethod + '_rp-hide-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-show-installment-plan-details').style.display = 'none';
        document.getElementById(paymentMethod + '_rp-installment-plan-details').style.display = 'block';
        document.getElementById(paymentMethod + '_rp-installment-plan-no-details').style.display = 'none';
    }

}
