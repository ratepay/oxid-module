<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  PayIntelligent
 * @package   PayIntelligent_RatePAY
 * @copyright (C) 2011 PayIntelligent GmbH  <http://www.payintelligent.de/>
 * @license	http://www.gnu.org/licenses/  GNU General Public License 3
 */
/**
 * Abstract class for RatePAY Request data providers
 * @extends oxSuperCfg
 */
abstract class pi_ratepay_RequestAbstract extends oxSuperCfg
{
    /**
     * Get customer number.
     * @return string|boolean
     */
    public function getCustomerNumber()
    {
        return $this->getUser()->oxuser__oxcustnr->value;
    }

    /**
     * Get fax number of customer, or false if customer has none.
     * @return string|boolean
     */
    public function getCustomerFax()
    {
        $fax = empty($this->getUser()->oxuser__oxfax->value) ? false : $this->getUser()->oxuser__oxfax->value;

        return $fax;
    }

    /**
     * Get mobile number of customer, or false if customer has none.
     * @return string|boolean
     */
    public function getCustomerMobilePhone()
    {
        $mobilePhone = empty($this->getUser()->oxuser__oxmobfon->value) ? false : $this->getUser()->oxuser__oxmobfon->value;

        return $mobilePhone;
    }

    /**
     * Get phone number of customer, or false if customer has none.
     * @return string|boolean
     */
    public function getCustomerPhone()
    {
        $phone = false;

        if (!empty($this->getUser()->oxuser__oxfon->value) || !empty($this->getUser()->oxuser__oxprivfon->value)) {
            if (!empty($this->getUser()->oxuser__oxfon->value)) {
                $phone = $this->getUser()->oxuser__oxfon->value;
            } else {
                $phone = $this->getUser()->oxuser__oxprivfon->value;
            }
        }


        return $phone;
    }

    /**
     * Get complete customer address.
     * @return array
     */
    public function getCustomerAddress()
    {
        $countryCode = oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $this->getUser()->oxuser__oxcountryid->value . "'");

        $address = array(
            'street'            => $this->getUser()->oxuser__oxstreet->value,
            'street-additional' => $this->getUser()->oxuser__oxaddinfo->value,
            'street-number'     => $this->getUser()->oxuser__oxstreetnr->value,
            'zip-code'      => $this->getUser()->oxuser__oxzip->value,
            'city'          => $this->getUser()->oxuser__oxcity->value,
            'country-code'  => $countryCode
        );

        return $address;
    }

    /**
     * Get complete delivery address.
     * @return array
     */
    public function getDeliveryAddress()
    {
        $order = oxNew('oxorder');
        $deliveryAddress = $order->getDelAddressInfo();

        if (is_null($deliveryAddress)){
            return false;
        }

        $countryCode = oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $deliveryAddress->oxaddress__oxcountryid->value . "'");

        $address = array(
            'first-name'    => $deliveryAddress->oxaddress__oxfname->value,
            'last-name'     => $deliveryAddress->oxaddress__oxlname->value,
            'company'       => $deliveryAddress->oxaddress__oxcompany->value,
            'street'        => $deliveryAddress->oxaddress__oxstreet->value,
            'street-number' => $deliveryAddress->oxaddress__oxstreetnr->value,
            'zip-code'      => $deliveryAddress->oxaddress__oxzip->value,
            'city'          => $deliveryAddress->oxaddress__oxcity->value,
            'country-code'  => $countryCode
        );

        return $address;
    }

    /**
     * Get company name of customer, or false if customer has none.
     * @return string|boolean
     */
    public function getCustomerCompanyName()
    {
        $company = false;

        if ($this->getUser()->oxuser__oxcompany->value != '' && $this->getUser()->oxuser__oxustid->value != '') {
            $company = $this->getUser()->oxuser__oxcompany->value;
        }

        return $company;
    }

    /**
     * Get customers date of birth
     * @return string
     */
    public function getCustomerDateOfBirth()
    {
        return $this->getUser()->oxuser__oxbirthdate->value;
    }

    /**
     * Get customers first name
     * @return string
     */
    public function getCustomerFirstName()
    {
        return $this->getUser()->oxuser__oxfname->value;
    }

    /**
     * Get customers last name
     * @return string
     */
    public function getCustomerLastName()
    {
        return $this->getUser()->oxuser__oxlname->value;
    }

    /**
     * Get where customer lives.
     * @return string
     */
    public function getCustomerNationality()
    {
        return oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $this->getUser()->oxuser__oxcountryid->value . "'");
    }

    /**
     * Get vat id of customers company, or false if customer has none.
     * @return string|boolean
     */
    public function getCustomerVatId()
    {
        $vatId = false;

        if ($this->getUser()->oxuser__oxcompany->value != '' && $this->getUser()->oxuser__oxustid->value != '') {
            $vatId = $this->getUser()->oxuser__oxustid->value;
        }

        return $vatId;
    }

    /**
     * Get customers e-mail
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getUser()->oxuser__oxusername->value;
    }

    /**
     * Get customers bank-data, owner can be retrieved either in session or if not set in $this->getUser().
     * @todo bank data persistence
     * @todo validate if bankdata is in session
     * @return array
     */
    public function getCustomerBankdata($paymentType)
    {
        $bankData          = array();
        $bankDataType      = $this->getSession()->getVariable($paymentType . '_bank_datatype');
        $bankAccountNumber = $this->getSession()->getVariable($paymentType . '_bank_account_number');
        $bankCode          = $this->getSession()->getVariable($paymentType . '_bank_code');
        $bankIban          = $this->getSession()->getVariable($paymentType . '_bank_iban');

        if ($bankDataType == 'classic') {
            $bankData['bankAccountNumber'] = $bankAccountNumber;
            $bankData['bankCode']          = $bankCode;
        } else {
            $bankData['bankIban'] = $bankIban;
        }

        return $bankData;
    }

    /**
     * Get customers gender, or 'U' (unknown) if none set.
     * @return string
     */
    public function getGender()
    {
        $salutation = strtoupper($this->getUser()->oxuser__oxsal->value);
        switch ($salutation) {
            default:
                $gender = 'U';
                break;
            case 'MR':
                $gender = 'M';
                break;
            case 'MRS':
                $gender = 'F';
                break;
        }

        return $gender;
    }

    protected function _getOwner($paymentType)
    {
        $elvUseCompany = $this->getSession()->getVariable('elv_use_company_name');

        $owner = null;
        if ($this->getSession()->hasVariable($paymentType . '_bank_owner')) {
            $owner = $this->getSession()->getVariable($paymentType . 'elv_bank_owner');
        } else {
            if (!empty($elvUseCompany) && $elvUseCompany == 1) {
                $owner = $this->getUser()->oxuser__oxcompany->value;
            } else {
                $owner = $this->getCustomerFirstName() . ' ' . $this->getCustomerLastName();
            }
        }

        return $owner;
    }
}
