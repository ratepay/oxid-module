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
 * Creation and initialising of RatePAY Requests (shop frontend)
 * @extends oxSuperCfg
 */
class pi_ratepay_RatepayRequest extends oxSuperCfg
{

    /**
     * pi_ratepay_rechnung or pi_ratepay_rate
     * @var string
     */
    private $_paymentType;

    /**
     * RatepayXML Service
     * @var pi_ratepay_xmlService
     */
    private $_xmlService;

    /**
     * RatePAY Data Provider
     * @var pi_ratepay_requestAbstract
     */
    private $_dataProvider;

    /**
     * DE or AT (or CH)
     * @var string
     */
    private $_country;

    /**
     * shopId
     * @var int
     */
    private $_shopId;

    /**
     * Profile Id
     * @var string
     */
    private $_profileId;

    /**
     * Security Coce
     * @var string
     */
    private $_securityCode;

    /**
     * Is shop set to UTF8 Mode
     * @var bool
     */
    private $_utfMode;

    /**
     * Class constructor
     * @param string $paymentType
     * @param pi_ratepay_RequestAbstract $dataProvider
     * @param pi_ratepay_xmlService $xmlService
     */
    public function __construct($paymentType, pi_ratepay_RequestAbstract $dataProvider = null, $xmlService = null, $extendedData = array())
    {
        parent::__construct();

        $this->_paymentType = $paymentType;
        $this->_country = ($extendedData['country']) ? $extendedData['country'] : false;
        $this->_shopId = $this->getConfig()->getShopId();
        $this->_shopId = oxNew('pi_ratepay_settings')->setShopIdToOne($this->_shopId);
        $this->_profileId = ($extendedData['profileId']) ? $extendedData['profileId'] : false;
        $this->_securityCode = ($extendedData['securityCode']) ? $extendedData['securityCode'] : false;
        $this->_dataProvider = $dataProvider;
        $this->_xmlService = isset($xmlService) ? $xmlService : pi_ratepay_xmlService::getInstance();
        $this->_utfMode = $this->getConfig()->isUtf();
    }

    /**
     * Do init payment request.
     * @return array
     */
    public function initPayment()
    {
        $operation = 'PAYMENT_INIT';

        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();

        $head = $this->_setRatepayHead($request, $operation);
        $this->_setRatepayHeadMeta($head);

        $initPayment = array(
            'request'  => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $this->_shopId)
        );

        return $initPayment;
    }
    
    /*
     * Do payment change request
     * return array
    */
    public function changePayment($trans_id, $strans_id, $subtype, $oid)
    {
        $currency = $this->getSession()->getBasket()->getBasketCurrency()->name;
        $shopId = $this->getConfig()->getShopId();
        if ($shopId == 'oxbaseshop'){
            $shopId = 1;
        }

        $operation = 'PAYMENT_CHANGE';

        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();

        $head = $this->_setRatepayHead($request, $operation, $trans_id, $strans_id, $subtype);

        $this->_setRatepayHeadExternal($head, $operation, $oid);
        $this->_setRatepayHeadMeta($head);
        $content = $request->addChild('content');
        $shoppingBasket = $content->addChild('shopping-basket');
        $shoppingBasket->addAttribute('amount', "0");
        $shoppingBasket->addAttribute('currency', $currency);
        $changePayment = array(
            'request'  => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $shopId)
        );
        return $changePayment;
    }

    /**
     * Do a request payment request.
     * @return array
     */
    public function requestPayment()
    {
        $operation = 'PAYMENT_REQUEST';

        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();

        $head = $this->_setRatepayHead($request, $operation);
        $this->_setRatepayHeadExternal($head, $operation);
        $this->_setRatepayHeadCustomerDevice($head);
        $this->_setRatepayHeadMeta($head);

        $content = $request->addChild('content');
        $content = $this->_setRatepayContentCustomer($content);
        $content = $this->_setRatepayContentBasket($content);
        $payment = $this->_setRatepayContentPayment($content);

        if ($this->_getPaymentType() == 'pi_ratepay_rate') {
            $installment = $payment->addChild('installment-details');
            $this->_setRatepayContentPaymentInstallment($installment);
            if ($this->_isRateElv()) {
                $payment->addChild('debit-pay-type', 'DIRECT-DEBIT');
            } else {
                $payment->addChild('debit-pay-type', 'BANK-TRANSFER');
            }
        }

        $requestPayment = array(
            'request'  => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $this->_shopId)
        );

        return $requestPayment;
    }

    /**
     * Do a confirm payment request.
     * @return array
     */
    public function confirmPayment()
    {
        $operation = 'PAYMENT_CONFIRM';
        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();

        $head = $this->_setRatepayHead($request, $operation);
        $this->_setRatepayHeadExternal($head, $operation);
        $this->_setRatepayHeadMeta($head);

        $confirmPayment = array(
            'request'  => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $this->_shopId)
        );

        return $confirmPayment;
    }

    /**
     * Do a confirm payment request.
     * @return array
     */
    public function configRequest()
    {
        $operation = 'CONFIGURATION_REQUEST';
        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();

        $head = $this->_setRatepayHead($request, $operation);
        $this->_setRatepayHeadMeta($head);

        $confirmPayment = array(
            'request'  => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $this->_shopId)
        );

        return $confirmPayment;
    }

    /**
     * Do a request profile request.
     * @return array
     */
    public function profileRequest($country = null)
    {
        $operation = 'PROFILE_REQUEST';
        $ratepay = $this->_getXmlService();
        $request = $ratepay->getXMLObject();
        $this->_setRatepayHead($request, $operation);
        $requestProfile = array(
            'request' => $request,
            'response' => $ratepay->paymentOperation($request, $this->_getPaymentMethod(), $this->_shopId, $country)
        );
        return $requestProfile;
    }

    /**
     * Generate head node for request xml
     *
     * @param SimpleXMLExtended $request
     * @param string $operation
     * @param string $subtype
     * @return SimpleXMLExtended
     */
    private function _setRatepayHead($request, $operation, $trans_id = null, $strans_id = null, $subtype = null)
    {
        $head = $request->addChild('head');
        $head->addChild('system-id', $this->_getRatepaySystemID());

        if ($operation != 'PAYMENT_INIT' && $operation != 'CONFIGURATION_REQUEST' && $operation != 'PROFILE_REQUEST' && $operation != 'PAYMENT_CHANGE') {
            $head->addChild('transaction-id', $this->_getDataProvider()->getTransactionId());
        }elseif ($operation == 'PAYMENT_CHANGE') {
            $head->addChild('transaction-id', $trans_id);
            $head->addChild('transaction-short-id', $strans_id);
        }

        $operation = $head->addChild('operation', $operation);

        if ($operation == "PAYMENT_CHANGE") {
            $operation->addAttribute('subtype', $subtype);
        }

        $this->_setRatepayHeadCredentials($head);

        return $head;
    }

    /**
     * Adds credentials to request XML.
     *
     * @param SimpleXMLExtended $head
     * @param string $paymentType
     */
    private function _setRatepayHeadCredentials($head)
    {
        $credential = $head->addChild('credential');
        if ($this->_getProfileId() && $this->_getSecurityCode()) {
            $profileId = $this->_getProfileId();
            $securityCode = $this->_getSecurityCode();
        } else {
            $paymentMethod = strtolower($this->_getPaymentMethod());
            $country = $this->_getCountryCodeById($this->getUser()->oxuser__oxcountryid->value);
            $settings = oxNew('pi_ratepay_settings');
            if ($country) {
                $settings->loadByType($paymentMethod, oxRegistry::getSession()->getVariable('shopId'), $country);
            } else {
                $settings->loadByType($paymentMethod, oxRegistry::getSession()->getVariable('shopId'));
            }
            $profileId = $settings->pi_ratepay_settings__profile_id->rawValue;
            $securityCode = $settings->pi_ratepay_settings__security_code->rawValue;
        }
        $credential->addChild('profile-id', $profileId);
        $credential->addChild('securitycode', $securityCode);
    }

    /**
     * Adds orderid to request XML.
     *
     * @param SimpleXMLExtended $head
     */
    private function _setRatepayHeadExternal($head, $operation, $oid = null)
    {
        $external = $head->addChild('external');

        if ($operation == 'PAYMENT_CONFIRM') {
            $external->addChild('order-id', $this->_getDataProvider()->getOrderId());
        }

        if ($operation == 'PAYMENT_CHANGE'){
            $external->addChild('order-id', $oid);
        }

        if ($operation == 'PAYMENT_REQUEST') {
            $external->addChild('merchant-consumer-id', $this->_getDataProvider()->getCustomerNumber());
        }
    }

    /**
     * Add shop name and version. Add also module version.
     * <system name=”<shopname>_<edition>” version=”<shopversion>_<moduleversion>”></system>
     *
     * @param SimpleXMLExtended $head
     */
    private function _setRatepayHeadMeta($head)
    {
        $meta = $head->addChild('meta');
        $systems = $meta->addChild('systems');
        $system = $systems->addChild('system');

        $system->addAttribute('name', 'OXID_' . oxRegistry::getConfig()->getEdition());
        $system->addAttribute('version', oxRegistry::getConfig()->getVersion() . '_' . pi_ratepay_util_utilities::PI_MODULE_VERSION);
    }

    /**
     * Adds customer-device information to request XML.
     *
     * @uses function _setRatepayHeadCustomerDeviceHttpHeader
     * @param SimpleXMLExtended $head
     */
    private function _setRatepayHeadCustomerDevice($head)
    {
        $customerDevice = $head->addChild('customer-device');
        $this->_setRatepayHeadCustomerDeviceDeviceToken($customerDevice);
    }

    /**
     * Adds device information to the header of the device fingerprint token
     *
     * @param SimpleXMLExtended $customerDevice
     */
    private function _setRatepayHeadCustomerDeviceDeviceToken($customerDevice)
    {
        $DeviceFingerprintToken = $this->getSession()->getVariable('pi_ratepay_dfp_token');

        if (!empty($DeviceFingerprintToken)) {
            $customerDevice->addChild('device-token', $DeviceFingerprintToken);
            $this->getSession()->deleteVariable('pi_ratepay_dfp_token');
        }
    }

    /**
     * Adds cutomer information (first-name, last-name, date-of-birth etc.) to the request XML.
     *
     * @uses function _setRatepayContentCustomerContacts
     * @uses function _setRatepayContentCustomerAddress
     * @param SimpleXMLExtended $content
     */
    private function _setRatepayContentCustomer($content)
    {
        $customer = $content->addChild('customer');

        $customer->addCDataChild('first-name', $this->_removeSpecialChars($this->_getDataProvider()->getCustomerFirstName()), $this->_utfMode);
        $customer->addCDataChild('last-name', $this->_removeSpecialChars($this->_getDataProvider()->getCustomerLastName()), $this->_utfMode);

        $company = $this->_getDataProvider()->getCustomerCompanyName();
        if ($company) {
            $customer->addCDataChild('company-name', $company, $this->_utfMode);
        }

        $customer->addChild('gender', $this->_getDataProvider()->getGender());
        $customer->addChild('date-of-birth', $this->_getDataProvider()->getCustomerDateOfBirth());
        $customer->addChild('ip-address', $this->_getRatepayCustomerIpAddress());

        $this->_setRatepayContentCustomerContacts($customer);
        $this->_setRatepayContentCustomerAddress($customer);
        if ($this->_getPaymentType() === 'pi_ratepay_elv' || $this->_isRateElv()) {
            $this->_setRatepayContentCustomerBankAccount($customer);
        }

        $customer->addChild('nationality', $this->_getDataProvider()->getCustomerNationality());
        $customer->addChild('customer-allow-credit-inquiry', 'yes');

        $vatId = $this->_getDataProvider()->getCustomerVatId();
        if ($vatId) {
            $customer->addChild('vat-id', $vatId);
        }

        return $content;
    }

    /**
     * Adds customer contact information to request XML.
     *
     * @uses function _setRatepayContentCustomerContactsPhone
     * @uses function _setRatepayContentCustomerContactsFax
     * @uses function _setRatepayContentCustomerContactsMobile
     * @param SimpleXMLExtended $customer
     */
    private function _setRatepayContentCustomerContacts($customer)
    {
        $contacts = $customer->addChild('contacts');
        $contacts->addChild('email', $this->_getDataProvider()->getCustomerEmail());

        $this->_setRatepayContenCustomerContactsPhoneNumbers($contacts, 'phone', $this->_getDataProvider()->getCustomerPhone());
        $this->_setRatepayContenCustomerContactsPhoneNumbers($contacts, 'mobile', $this->_getDataProvider()->getCustomerMobilePhone());
    }

    /**
     * Add phone numbers to request XML.
     *
     * @param SimpleXMLExtended $contacts
     * @param string $type
     * @param string $number
     */
    private function _setRatepayContenCustomerContactsPhoneNumbers($contacts, $type, $number)
    {
        if ($number) {
            $phoneNode = $contacts->addChild($type);
            $phoneNode->addChild('direct-dial', $number);
        }
    }

    /**
     * Adds customers address (billing and shipping) to request XML.
     *
     * @uses function _setRatepayContentCustomerAddressBilling
     * @uses function _setRatepayContentCustomerAddressShipping
     * @param SimpleXMLExtended $customer
     */
    private function _setRatepayContentCustomerAddress($customer)
    {
        $addresses = $customer->addChild('addresses');

        $customerAddress = $this->_getDataProvider()->getCustomerAddress();
        $deliveryAddress = $this->_getDataProvider()->getDeliveryAddress();

        $this->_setRatepayContentCustomerAddressesBilling($addresses, $customerAddress);
        if ($deliveryAddress) {
            $this->_setRatepayContentCustomerAddressesDelivery($addresses, $deliveryAddress);
        }
    }

    /**
     * Adds customer billing address to request xml
     *
     * @param SimpleXMLExtended $addresses
     */
    private function _setRatepayContentCustomerAddressesBilling($addresses, $address)
    {
        $street = $this->_removeSpecialChars($address['street']);
        $city = $this->_removeSpecialChars($address['city']);

        $billingAddress = $addresses->addChild('address');
        $billingAddress->addAttribute('type', 'BILLING');
        $billingAddress->addCDataChild('street', $street, $this->_utfMode);
        if(!empty($address['street-additional'])){
            $billingAddress->addCDataChild('street-additional', $address['street-additional']);
        }
        $billingAddress->addChild('street-number', $address['street-number']);
        $billingAddress->addChild('zip-code', $address['zip-code']);
        $billingAddress->addCDataChild('city', $city, $this->_utfMode);
        $billingAddress->addChild('country-code', $address['country-code']);
    }

    /**
     * Adds customer delivery address to request xml
     *
     * @param SimpleXMLExtended $addresses
     */
    private function _setRatepayContentCustomerAddressesDelivery($addresses, $address)
    {
        $firstname = $this->_removeSpecialChars($address['first-name']);
        $lastname  = $this->_removeSpecialChars($address['last-name']);
        $company   = $this->_removeSpecialChars($address['company']);
        $street    = $this->_removeSpecialChars($address['street']);
        $city      = $this->_removeSpecialChars($address['city']);

        $deliveryAddress = $addresses->addChild('address');
        $deliveryAddress->addAttribute('type', 'DELIVERY');
        $deliveryAddress->addCDataChild('first-name', $firstname, $this->_utfMode);
        $deliveryAddress->addCDataChild('last-name', $lastname, $this->_utfMode);
        $deliveryAddress->addCDataChild('company', $company, $this->_utfMode);
        $deliveryAddress->addCDataChild('street', $street, $this->_utfMode);
        $deliveryAddress->addChild('street-number', $address['street-number']);
        $deliveryAddress->addChild('zip-code', $address['zip-code']);
        $deliveryAddress->addCDataChild('city', $city, $this->_utfMode);
        $deliveryAddress->addChild('country-code', $address['country-code']);
    }

    /**
     * Adds customer bank account data to request xml
     * @param SimpleXMLExtended $customer
     */
    private function _setRatepayContentCustomerBankAccount($customer)
    {
        $bankdata = $this->_getDataProvider()->getCustomerBankdata($this->_getPaymentType());
        $bankAccountOwner = $this->_removeSpecialChars($this->_getDataProvider()->getCustomerFirstName()) . " " . $this->_removeSpecialChars($this->_getDataProvider()->getCustomerLastName());

        $bankAccount = $customer->addChild('bank-account');
        $bankAccount->addCDataChild('owner', (!mb_detect_encoding($bankAccountOwner, 'UTF-8', true)) ? utf8_encode($bankAccountOwner) : $bankAccountOwner);
        if (isset($bankdata['bankAccountNumber'])) {
            $bankAccount->addChild('bank-account-number', $bankdata['bankAccountNumber']);
        }
        if (isset($bankdata['bankCode'])) {
            $bankAccount->addChild('bank-code', $bankdata['bankCode']);
        }
        if (isset($bankdata['bankIban'])) {
            $bankAccount->addChild('iban', $bankdata['bankIban']);
        }
        if (isset($bankdata['bankBic'])) {
            $bankAccount->addChild('bic-swift', $bankdata['bankBic']);
        }
    }

    /**
     * Adds basket contents to request XML.
     *
     * @uses function _setRatepayContentBasket
     * @param SimpleXMLExtended $content
     */
    private function _setRatepayContentBasket($content)
    {
        $shoppingBasket = $content->addChild('shopping-basket');
        $shoppingBasket->addAttribute('amount', $this->_getFormattedNumber($this->_getDataProvider()->getBasketAmount()));
        $shoppingBasket->addAttribute('currency', $this->_getDataProvider()->getActBasketCurrency()->name);
        $this->_setRatepayContentBasketItems($shoppingBasket);
        return $content;
    }

    /**
     * Adds child node 'items' to 'shopping-basket' child node of request XML content.
     *
     * @uses function _setRatepayContentBasketItemsItem
     * @param SimpleXMLExtended $shoppingBasket
     */
    private function _setRatepayContentBasketItems($shoppingBasket)
    {
        $items = $shoppingBasket->addChild('items');
        $this->_setRatepayContentBasketItemsItem($items);
    }

    /**
     * Adds items to request xml. 'item' nodes consist of several item specific information: like article-number,
     * quantity, unit-price, tax etc.
     *
     * @param SimpleXMLExtended $items
     */
    private function _setRatepayContentBasketItemsItem($items)
    {
        $articles = $this->_getDataProvider()->getBasketArticles();
        foreach ($articles as $article) {
            $item = $items->addCDataChild('item', $article->getTitle(), $this->_utfMode);
            $item->addAttribute('article-number', $article->getArticleNumber());
            $item->addAttribute('quantity', $article->getQuantity());
            $item->addAttribute('unit-price', $this->_getFormattedNumber($article->getUnitPrice()));
            $item->addAttribute('total-price', $this->_getFormattedNumber($article->getPrice()));
            $item->addAttribute('tax', $this->_getFormattedNumber($article->getVatValue()));
        }

        /**
         * add wrapping costs, delivery costs, etc… if null add 0,00 articles
         **/

        $basket = $this->_getDataProvider()->getSession()->getBasket();

        if (method_exists($basket, 'getWrappingCost') && $basket->getWrappingCost()) {
            $wrappingCosts = $basket->getWrappingCost()->getPrice();
            $wrappingNettoPrice = $basket->getWrappingCost()->getNettoPrice();
            $wrappingVatValue = $basket->getWrappingCost()->getVatValue();
        } elseif (method_exists($basket, 'getFWrappingCosts') && $basket->getFWrappingCosts()) {
            $wrappingCosts = $basket->getFWrappingCosts();
            if ($basket->getWrappCostNet() > 0) {
                $wrappingNettoPrice = $basket->getWrappCostNet();
                $wrappingVatValue = $basket->getWrappCostVat();
            } else {
                $wrappingNettoPrice = $wrappingCosts;
                $wrappingVatValue = 0;
            }
        } else {
            $wrappingCosts = 0;
        }

        if ($wrappingCosts != 0) {
            $item = $items->addChild('item', 'Wrapping Cost');
            $item->addAttribute('article-number', 'oxwrapping');
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', $this->_getFormattedNumber($wrappingNettoPrice));
            $item->addAttribute('total-price', $this->_getFormattedNumber($wrappingNettoPrice));
            $item->addAttribute('tax', $this->_getFormattedNumber($wrappingVatValue));
        }

        if (method_exists($basket, 'getGiftCardCost') && $basket->getGiftCardCost()) {
            $giftcardCosts = $basket->getGiftCardCost()->getPrice();
            $giftcardNettoPrice = $basket->getGiftCardCost()->getNettoPrice();
            $giftcardVatValue = $basket->getGiftCardCost()->getVatValue();
        } elseif (method_exists($basket, 'getFGiftCardCosts') && $basket->getFGiftCardCosts()) {
            $giftcardCosts = $basket->getFGiftCardCosts();
            if ($basket->getGiftCardCostNet() > 0) {
                $giftcardNettoPrice = $basket->getGiftCardCostNet();
                $giftcardVatValue = $basket->getGiftCardCostVat();
            } else {
                $giftcardNettoPrice = $giftcardCosts;
                $giftcardVatValue = 0;
            }
        } else {
            $giftcardCosts = 0;
        }

        if ($giftcardCosts > 0) {
            $item = $items->addChild('item', 'Giftcard Cost');
            $item->addAttribute('article-number', 'oxgiftcard');
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', $this->_getFormattedNumber($giftcardNettoPrice));
            $item->addAttribute('total-price', $this->_getFormattedNumber($giftcardNettoPrice));
            $item->addAttribute('tax', $this->_getFormattedNumber($giftcardVatValue));
        }

        if (method_exists($basket, 'getDeliveryCost') && $basket->getDeliveryCost()) {
            $deliveryCosts = $basket->getDeliveryCost()->getPrice();
            $deliveryNettoPrice = $basket->getDeliveryCost()->getNettoPrice();
            $deliveryVatValue = $basket->getDeliveryCost()->getVatValue();
        } elseif (method_exists($basket, 'getDeliveryCosts') && $basket->getDeliveryCosts()) {
            $deliveryCosts = $basket->getDeliveryCosts();
            if ($basket->getDelCostNet() > 0) {
                $deliveryNettoPrice = $basket->getDelCostNet();
                $deliveryVatValue = $basket->getDelCostVat();
            } else {
                $deliveryNettoPrice = $deliveryCosts;
                $deliveryVatValue = 0;
            }
        } else {
            $deliveryCosts = 0;
        }
        
        if ($deliveryCosts > 0) {
            $item = $items->addChild('item', 'Delivery Cost');
            $item->addAttribute('article-number', 'oxdelivery');
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', $this->_getFormattedNumber($deliveryNettoPrice));
            $item->addAttribute('total-price', $this->_getFormattedNumber($deliveryNettoPrice));
            $item->addAttribute('tax', $this->_getFormattedNumber($deliveryVatValue));
        }
        
        if (method_exists($basket, 'getPaymentCost') && $basket->getPaymentCost()) {
            $paymentCosts = $basket->getPaymentCost()->getPrice();
            $paymentNettoPrice = $basket->getPaymentCost()->getNettoPrice();
            $paymentVatValue = $basket->getPaymentCost()->getVatValue();
        } elseif (method_exists($basket, 'getPaymentCosts') && $basket->getPaymentCosts()) {
            $paymentCosts = $basket->getPaymentCosts();
            if ($basket->getPayCostNet() > 0) {
                $paymentNettoPrice = $basket->getPayCostNet();
                $paymentVatValue = $basket->getPayCostVat();
            } else {
                $paymentNettoPrice = $paymentCosts;
                $paymentVatValue = 0;
            }
        } else {
            $paymentCosts = 0;
        }            

        if ($paymentCosts > 0) {
            $item = $items->addChild('item', 'Payment Cost');
            $item->addAttribute('article-number', 'oxpayment');
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', $this->_getFormattedNumber($paymentNettoPrice));
            $item->addAttribute('total-price', $this->_getFormattedNumber($paymentNettoPrice));
            $item->addAttribute('tax', $this->_getFormattedNumber($paymentVatValue));
        }

        if (method_exists($basket, 'getTrustedShopProtectionCost')) {
            $tsItem = $basket->getTrustedShopProtectionCost();
        } elseif (method_exists($basket, 'getTsProtectionCosts')) {
            $tsItem = $basket->getTsProtectionCosts();
        } else {
            $tsItem = false;
        }

        if (method_exists($basket, 'getTrustedShopProtectionCost') && $basket->getTrustedShopProtectionCost()) {
            $tsProtectionCosts = $basket->getTrustedShopProtectionCost()->getPrice();
            $tsProtectionNettoPrice = $basket->getTrustedShopProtectionCost()->getNettoPrice();
            $tsProtectionVatValue = $basket->getTrustedShopProtectionCost()->getVatValue();
        } elseif (method_exists($basket, 'getTsProtectionCosts') && $basket->getTsProtectionCosts()) {
            $tsProtectionCosts = $basket->getTsProtectionCosts();
            if ($basket->getTsProtectionNet() > 0) {
                $tsProtectionNettoPrice = $basket->getTsProtectionNet();
                $tsProtectionVatValue = $basket->getTsProtectionVat();
            } else {
                $tsProtectionNettoPrice = $tsProtectionCosts;
                $tsProtectionVatValue = 0;
            }
        } else {
            $tsProtectionCosts = 0;
        }

        if ($tsProtectionCosts > 0) {
            $item = $items->addChild('item', 'TS Protection Cost');
            $item->addAttribute('article-number', 'oxtsprotection');
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', $this->_getFormattedNumber($tsProtectionNettoPrice));
            $item->addAttribute('total-price', $this->_getFormattedNumber($tsProtectionNettoPrice));
            $item->addAttribute('tax', $this->_getFormattedNumber($tsProtectionVatValue));
        }

        if (count($basket->getVouchers())) {

            foreach ($basket->getVouchers() as $voucher) {
                $vNr=$voucher->sVoucherId;
                $item = $items->addCDataChild('item', $this->_getVoucherTitle($vNr), $this->_utfMode);
                $item->addAttribute('article-number', $voucher->sVoucherNr);
                $item->addAttribute('quantity', 1);
                $item->addAttribute('unit-price', "-" . $this->_getFormattedNumber($voucher->dVoucherdiscount));
                $item->addAttribute('total-price', "-" . $this->_getFormattedNumber($voucher->dVoucherdiscount));
                $item->addAttribute('tax', $this->_getFormattedNumber("0"));
            }
        }

        if ($basket->getTotalDiscount() && $basket->getTotalDiscount()->getBruttoPrice() > 0) {
            $item = $items->addChild('item', "discount");
            $item->addAttribute('article-number', "discount");
            $item->addAttribute('quantity', 1);
            $item->addAttribute('unit-price', "-" . $this->_getFormattedNumber($basket->getTotalDiscount()->getNettoPrice()));
            $item->addAttribute('total-price', "-" . $this->_getFormattedNumber($basket->getTotalDiscount()->getNettoPrice()));
            $item->addAttribute('tax', $this->_getFormattedNumber("0"));
        }
    }

    /**
     * Adds payment method specific information to request XML. Differentiates between Rate (installment) and
     * Rechnung (invoice).
     *
     * @param SimpleXMLExtended $content
     * @param string $paymentType
     */
    private function _setRatepayContentPayment($content)
    {
        $payment = $content->addChild('payment');

        $payment->addAttribute('currency', $this->_getDataProvider()->getActBasketCurrency()->name);
        $payment->addAttribute('method', $this->_getPaymentMethod());
        $payment->addChild('amount', $this->_getFormattedNumber($this->_getDataProvider()->getPaymentAmount()));

        return $payment;
    }

    /**
     * Add installment information to request XML.
     *
     * @param SimpleXMLExtended $installment
     */
    private function _setRatepayContentPaymentInstallment($installment)
    {
        $installment->addChild('installment-number', $this->_getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_number_of_rates'), 0));
        $installment->addChild('installment-amount', $this->_getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_rate')));
        $installment->addChild('last-installment-amount', $this->_getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_last_rate')));
        $installment->addChild('interest-rate', $this->_getFormattedNumber($this->getSession()->getVariable('pi_ratepay_rate_interest_rate')));
        $installment->addChild('payment-firstday', $this->getSession()->getVariable('pi_ratepay_rate_payment_firstday'));
    }

    /**
     * Helper Method which removes special characters from strings.
     *
     * @uses function _removeSpecialChar
     * @param string $str
     * @return string
     */
    private function _removeSpecialChars($str)
    {
        $str = html_entity_decode($str);

        $search = array("–", "´", "‹", "›", "‘", "’", "‚", "“", "”", "„", "‟", "•", "‒", "―", "—", "™", "¼", "½", "¾");
        $replace = array("-", "'", "<", ">", "'", "'", ",", '"', '"', '"', '"', "-", "-", "-", "-", "TM", "1/4", "1/2", "3/4");
        return str_replace($search, $replace, $str);
    }

    /**
     * Get server address. (example: http://localhost/eshop/
     *
     * @return string
     */
    private function _getRatepaySystemID()
    {
        $systemId = $_SERVER['SERVER_ADDR'];
        return $systemId;
    }

    /**
     * Get customers IP Address
     *
     * @return string
     */
    private function _getRatepayCustomerIpAddress()
    {
        $customerIp = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $customerIp = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $customerIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $customerIp = $_SERVER['REMOTE_ADDR'];
        }

        return $customerIp;
    }

    /**
     * Get RatePAY XML-Service
     * @return pi_ratepay_xmlService
     */
    private function _getXmlService()
    {
        return $this->_xmlService;
    }

    /**
     * Get payment type as registered in oxid.
     * @return string pi_ratepay_rechnung or pi_ratepay_rate
     */
    private function _getPaymentType()
    {
        return $this->_paymentType;
    }

    /**
     * Get payment method, invoice or installment.
     * @create convenience method for all classes
     * @return string
     */
    private function _getPaymentMethod()
    {
        $util = new pi_ratepay_util_Utilities();
        return $util->getPaymentMethod($this->_getPaymentType());
    }

    /**
     * Get formattet number
     * @param string $str
     * @param int $decimal
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    private function _getFormattedNumber($str, $decimal = 2, $dec_point = ".", $thousands_sep = "")
    {
        $util = new pi_ratepay_util_Utilities();
        return $util->getFormattedNumber($str, $decimal, $dec_point, $thousands_sep);
    }

    /**
     * Get current country
     * @return string DE or AT (or CH)
     */
    private function _getCountry()
    {
        return $this->_country;
    }
    /**
     * Get Profile Id
     * @return string
     */
    private function _getProfileId()
    {
        return $this->_profileId;
    }
    /**
     * Get Security Code
     * @return string
     */
    private function _getSecurityCode()
    {
        return $this->_securityCode;
    }

    /**
     * Get data provider for request.
     * @return pi_ratepay_requestAbstract
     */
    private function _getDataProvider()
    {
        return $this->_dataProvider;
    }

    private function _isRateElv()
    {
        $isRateElv = false;
        $session = new oxSession;
        $settings = oxNew('pi_ratepay_settings');
        $settings->loadByType($this->_getPaymentMethod('pi_ratepay_rate'), $session->getVariable('shopId'));

        if ($this->getSession()->getVariable('pi_rp_rate_pay_method') === 'pi_ratepay_rate_radio_elv'
            && $settings->pi_ratepay_settings__activate_elv->rawValue == 1
        ) {
            $isRateElv = true;

            $bankDataSessionKeys = array(
                $this->_getPaymentType() . '_bank_owner',
                $this->_getPaymentType() . '_bank_name',
                $this->_getPaymentType() . '_bank_account_number',
                $this->_getPaymentType() . '_bank_code',
                $this->_getPaymentType() . '_bank_iban',
                $this->_getPaymentType() . '_bank_bic'
            );

            foreach ($bankDataSessionKeys as $key) {
                if (!$this->getSession()->hasVariable($key)) {
                    $isRateElv = false;
                    break;
                }
            }
        }

        return $isRateElv;
    }

    private function _getVoucherTitle($oxid){
        $voucher = oxDB::getDb()->getOne("SELECT OXVOUCHERSERIEID FROM oxvouchers WHERE OXID ='" . $oxid . "'");
        return oxDb::getDb()->getOne("SELECT OXSERIENR FROM oxvoucherseries WHERE OXID ='" . $voucher . "'");
    }

    private function _getCountryCodeById($countryId) {
        return oxDb::getDb()->getOne("SELECT OXISOALPHA2 FROM oxcountry WHERE OXID = '" . $countryId . "'");
    }

}
