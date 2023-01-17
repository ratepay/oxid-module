<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * RatePAY Rate (installment) manager. Checks if basket is set, if user is set
 * and for RatePAY session variables.
 * @package   PayIntelligent_RatePAY
 * @extends oxUBase
 */
class pi_ratepay_rate_Calc extends oxUBase
{

    /**
     * {@inheritdoc}
     *
     * @see oxUBase::init()
     */
    public function init()
    {
        parent::init();

        $this->_sThisTemplate = 'pi_ratepay_rate_calc.tpl';
    }

    /**
     * {@inheritdoc}
     *
     * Also adds template variable 'piTotalAmount' (brutto price, rounded).
     *
     * @see oxUBase::render()
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->checkUser();

        $basket = $this->getSession()->getBasket();
        $this->addTplParam(
            'piTotalAmount',
            number_format($basket->getPrice()->getBruttoPrice(), 2, ",", "")
        );

        return $this->_sThisTemplate;
    }

    /**
     * Checks if basket is set if not redirects to basket. Checks if user and
     * basket are set if not redirects to start page.
     */
    public function checkUser()
    {
        $myConfig = $this->getConfig();

        if ($myConfig->getConfigParam('blPsBasketReservationEnabled')) {
            $this->getSession()->getBasketReservations()->renewExpiration();
        }

        $oBasket = $this->getSession()->getBasket();
        if ($myConfig->getConfigParam('blPsBasketReservationEnabled')
            && (!$oBasket || ( $oBasket && !$oBasket->getProductsCount() ))
        ) {
            oxRegistry::getUtils()->redirect(
                $myConfig->getShopHomeURL() . 'cl=basket'
            );
        }

        $oUser = $this->getUser();
        if (!$oBasket
            || !$oUser
            || ( $oBasket && !$oBasket->getProductsCount() )
        ) {
            oxRegistry::getUtils()->redirect(
                $myConfig->getShopHomeURL() . 'cl=start'
            );
        }
    }

    /**
     * Checks if RatePAY Rate (installment) data is stored in session.
     */
    public function check()
    {
        $myConfig = $this->getConfig();
        $checking = true;

        // test for these variables in session
        $ratepaySessionVariables = array(
            'pi_ratepay_rate_total_amount',
            'pi_ratepay_rate_amount',
            'pi_ratepay_rate_interest_amount',
            'pi_ratepay_rate_service_charge',
            'pi_ratepay_rate_annual_percentage_rate',
            'pi_ratepay_rate_monthly_debit_interest',
            'pi_ratepay_rate_number_of_rates',
            'pi_ratepay_rate_rate',
            'pi_ratepay_rate_last_rate'
        );

        foreach ($ratepaySessionVariables as $sessionVariable) {
            if (!$this->getSession()->hasVariable($sessionVariable)
                || $this->getSession()->getVariable($sessionVariable) == ''
            ) {
                $checking = false;
                break;
            }
        }

        if ($checking) {
            oxRegistry::getUtils()->redirect(
                $myConfig->getShopHomeURL() . 'cl=order'
            );
        } else {
            oxRegistry::getUtils()->redirect(
                $myConfig->getShopHomeURL()
                . 'cl=pi_ratepay_rate_calc&fnc=calculateError'
            );
        }
    }

    /**
     * [_calculateError description]
     */
    public function calculateError()
    {
        $this->addTplParam('pierror', '-461');
    }

}
