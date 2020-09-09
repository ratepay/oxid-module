/**
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package pi_ratepay_rate_calculator
 * Code by PayIntelligent GmbH  <http://www.payintelligent.de/>
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
