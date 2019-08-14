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
 * @license http://www.gnu.org/licenses/  GNU General Public License 3
 */

/**
 * Module information
 */
$librarySubModel = 'pi/pi_ratepay/library/src/Model/Request/SubModel/';
$libraryModel    = 'pi/pi_ratepay/library/src/Model/';
$librarySubModelContent = 'RatePAY\Model\Request\SubModel\Content';

$aModule = array(
    'id'           => 'pi_ratepay',
    'title'        => 'RatePAY',
    'description'  => array(
        'de' => 'Bezahlung mit RatePAY',
        'en' => 'Payment with RatePAY'
    ),
    'thumbnail'    => 'ratepay_logo.png',
    'lang'         => 'en',
    'version'      => '5.0.3',
    'author'       => 'RatePAY GmbH',
    'email'        => 'integration@ratepay.com',
    'url'          => 'http://www.ratepay.com/',
    'extend'       => array(
        // controllers->admin
        'module_config'     => 'pi/pi_ratepay/extend/application/controllers/admin/pi_ratepay_module_config',
        // conrollers
        'payment'           => 'pi/pi_ratepay/extend/application/controllers/pi_ratepay_payment',
        'order'             => 'pi/pi_ratepay/extend/application/controllers/pi_ratepay_order',
        // core
        'oxshopcontrol'     => 'pi/pi_ratepay/extend/core/pi_ratepay_shopcontrol'
    ),
    'templates' => array(
        // views->admin
        'pi_ratepay_log.tpl'                            => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_log.tpl',
        'pi_ratepay_log_list.tpl'                       => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_log_list.tpl',
        'pi_ratepay_log_main.tpl'                       => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_log_main.tpl',
        'pi_ratepay_details.tpl'                        => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_details.tpl',
        'pi_ratepay_no_details.tpl'                     => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_no_details.tpl',
        'pi_ratepay_profile.tpl'                        => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_profile.tpl',
        'pi_ratepay_profile_list.tpl'                   => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_profile_list.tpl',
        'pi_ratepay_profile_main.tpl'                   => 'pi/pi_ratepay/application/views/admin/tpl/pi_ratepay_profile_main.tpl',
    ),
    'files' => array(
        // controllers -> admin
        'pi_ratepay_adminview_base'                     => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_adminview_base.php',
        'pi_ratepay_adminlist_base'                     => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_adminlist_base.php',
        'pi_ratepay_log'                                => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_log.php',
        'pi_ratepay_log_list'                           => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_log_list.php',
        'pi_ratepay_log_main'                           => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_log_main.php',
        'pi_ratepay_Details'                            => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_details.php',
        'pi_ratepay_Profile'                            => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_profile.php',
        'pi_ratepay_Profile_list'                       => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_profile_list.php',
        'pi_ratepay_Profile_main'                       => 'pi/pi_ratepay/application/controllers/admin/pi_ratepay_profile_main.php',
        // controllers
        'pi_ratepay_rate_Calc'                          => 'pi/pi_ratepay/application/controllers/pi_ratepay_rate_calc.php',
        // models
        'pi_ratepay_Logs'                               => 'pi/pi_ratepay/application/models/pi_ratepay_logs.php',
        'pi_ratepay_LogsList'                           => 'pi/pi_ratepay/application/models/pi_ratepay_logslist.php',
        'pi_ratepay_Settings'                           => 'pi/pi_ratepay/application/models/pi_ratepay_settings.php',
        // core
        'ModelFactory'                                  => 'pi/pi_ratepay/core/ModelFactory.php',
        'pi_ratepay_DetailsViewData'                    => 'pi/pi_ratepay/core/pi_ratepay_detailsviewdata.php',
        'pi_ratepay_History'                            => 'pi/pi_ratepay/core/pi_ratepay_history.php',
        'pi_ratepay_HistoryList'                        => 'pi/pi_ratepay/core/pi_ratepay_historylist.php',
        'pi_ratepay_LogsService'                        => 'pi/pi_ratepay/core/pi_ratepay_logsservice.php',
        'pi_ratepay_OrderDetails'                       => 'pi/pi_ratepay/core/pi_ratepay_orderdetails.php',
        'pi_ratepay_Orders'                             => 'pi/pi_ratepay/core/pi_ratepay_orders.php',
        'pi_ratepay_RateDetails'                        => 'pi/pi_ratepay/core/pi_ratepay_ratedetails.php',
        'pi_ratepay_RequestAbstract'                    => 'pi/pi_ratepay/core/pi_ratepay_requestabstract.php',
        'pi_ratepay_RequestDataBackend'                 => 'pi/pi_ratepay/core/pi_ratepay_requestdatabackend.php',
        'pi_ratepay_RequestOrderArticle'                => 'pi/pi_ratepay/core/pi_ratepay_requestorderarticle.php',
        'pi_ratepay_util_Utilities'                     => 'pi/pi_ratepay/core/pi_ratepay_util_utilities.php',
        'pi_ratepay_events'                             => 'pi/pi_ratepay/core/pi_ratepay_events.php',
        // libs
        'Pi_Util_Encryption_EncryptionAbstract'         => 'pi/pi_ratepay/Pi/Util/Encryption/EncryptionAbstract.php',
        'Pi_Util_Encryption_OxEncryption'               => 'pi/pi_ratepay/Pi/Util/Encryption/OxEncryption.php',
        'Pi_Util_Encryption_PrivateKey'                 => 'pi/pi_ratepay/Pi/Util/Encryption/PrivateKey.php',
        'RatePAY\RequestBuilder'                        => 'pi/pi_ratepay/library/src/RequestBuilder.php',
        'RatePAY\ModelBuilder'                          => 'pi/pi_ratepay/library/src/ModelBuilder.php',
        'RatePAY\Service\ModelMapper'                   => 'pi/pi_ratepay/library/src/Service/ModelMapper.php',
        'RatePAY\Exception\ModelException'              => 'pi/pi_ratepay/library/src/Exception/ModelException.php',
        'RatePAY\Exception\ExceptionAbstract'           => 'pi/pi_ratepay/library/src/Exception/ExceptionAbstract.php',
        'RatePAY\Model\Request\SubModel\Head'           => $librarySubModel . 'Head.php',
        $librarySubModelContent                         => $librarySubModel . 'Content.php',
        'RatePAY\Model\Request\SubModel\AbstractModel'  => $librarySubModel . 'AbstractModel.php',
        'RatePAY\Model\Request\SubModel\Head\Credential'=> $librarySubModel . 'Head/Credential.php',
        'RatePAY\Model\Request\SubModel\Head\Meta'      => $librarySubModel . 'Head/Meta.php',
        'RatePAY\Model\Request\SubModel\Head\CustomerDevice' => $librarySubModel . 'Head/CustomerDevice.php',
        'RatePAY\Model\Request\SubModel\Head\External'      => $librarySubModel . 'Head/External.php',
        'RatePAY\Model\Request\SubModel\Head\External\Tracking'      => $librarySubModel . 'Head/External/Tracking.php',
        'RatePAY\Model\Request\SubModel\Head\Meta\Systems'          => $librarySubModel . 'Head/Meta/Systems.php',
        'RatePAY\Model\Request\SubModel\Head\Meta\Systems\System'   => $librarySubModel . 'Head/Meta/Systems/System.php',
        'RatePAY\Model\Request\SubModel\Constants'      => $librarySubModel . 'Constants.php',
        'RatePAY\Exception\RequestException'            => 'pi/pi_ratepay/library/src/Exception/RequestException.php',
        'RatePAY\Exception\RuleSetException'            => 'pi/pi_ratepay/library/src/Exception/RuleSetException.php',
        'RatePAY\Exception\CurlException'               => 'pi/pi_ratepay/library/src/Exception/CurlException.php',
        'RatePAY\Exception\FrontendException'           => 'pi/pi_ratepay/library/src/Exception/FrontendException.php',
        $librarySubModelContent .  '\Additional'        => $librarySubModel . 'Content/Additional.php',
        'RatePAY\Model\Request\ProfileRequest'          => $libraryModel . 'Request/ProfileRequest.php',
        'RatePAY\Model\Request\AbstractRequest'         => $libraryModel . 'Request/AbstractRequest.php',
        'RatePAY\Model\Request\CalculationRequest'      => $libraryModel . 'Request/CalculationRequest.php',
        'RatePAY\Model\Request\ConfigurationRequest'    => $libraryModel . 'Request/ConfigurationRequest.php',
        'RatePAY\Model\Request\ConfirmationDeliver'     => $libraryModel . 'Request/ConfirmationDeliver.php',
        'RatePAY\Model\Request\PaymentChange'           => $libraryModel . 'Request/PaymentChange.php',
        'RatePAY\Model\Request\PaymentInit'             => $libraryModel . 'Request/PaymentInit.php',
        'RatePAY\Model\Request\PaymentQuery'            => $libraryModel . 'Request/PaymentQuery.php',
        'RatePAY\Model\Request\PaymentRequest'          => $libraryModel . 'Request/PaymentRequest.php',
        'RatePAY\Model\Request\PaymentConfirm'          => $libraryModel . 'Request/PaymentConfirm.php',
        'RatePAY\Service\Util'                          => 'pi/pi_ratepay/library/src/Service/Util.php',
        'RatePAY\Service\DeviceFingerprint'             => 'pi/pi_ratepay/library/src/Service/DeviceFingerprint.php',
        'RatePAY\Service\LanguageService'               => 'pi/pi_ratepay/library/src/Service/LanguageService.php',
        'RatePAY\Service\CommunicationService'          => 'pi/pi_ratepay/library/src/Service/CommunicationService.php',
        'RatePAY\Service\SimpleXmlExtended'             => 'pi/pi_ratepay/library/src/Service/SimpleXmlExtended.php',
        'RatePAY\Service\ValidateGatewayResponse'       => 'pi/pi_ratepay/library/src/Service/ValidateGatewayResponse.php',
        'RatePAY\Service\XmlBuilder'                    => 'pi/pi_ratepay/library/src/Service/XmlBuilder.php',
        'RatePAY\Model\Response\ProfileRequest'         => $libraryModel . 'Response/ProfileRequest.php',
        'RatePAY\Model\Response\AbstractResponse'       => $libraryModel . 'Response/AbstractResponse.php',
        'RatePAY\Model\Response\CalculationRequest'     => $libraryModel . 'Response/CalculationRequest.php',
        'RatePAY\Model\Response\ConfigurationRequest'   => $libraryModel . 'Response/ConfigurationRequest.php',
        'RatePAY\Model\Response\ConfirmationDeliver'    => $libraryModel . 'Response/ConfirmationDeliver.php',
        'RatePAY\Model\Response\PaymentChange'          => $libraryModel . 'Response/PaymentChange.php',
        'RatePAY\Model\Response\PaymentInit'            => $libraryModel . 'Response/PaymentInit.php',
        'RatePAY\Model\Response\PaymentQuery'           => $libraryModel . 'Response/PaymentQuery.php',
        'RatePAY\Model\Response\PaymentRequest'         => $libraryModel . 'Response/PaymentRequest.php',
        'RatePAY\Model\Response\PaymentConfirm'         => $libraryModel . 'Response/PaymentConfirm.php',
        'RatePAY\Model\Response\TraitTransactionId'         => $libraryModel . 'Response/TraitTransactionId.php',
        $librarySubModelContent . '\Additional'             => $librarySubModel . 'Content/Additional.php',
        $librarySubModelContent . '\Invoicing'              => $librarySubModel . 'Content/Invoicing.php',
        $librarySubModelContent . '\Payment'                => $librarySubModel . 'Content/Payment.php',
        $librarySubModelContent . '\ShoppingBasket'         => $librarySubModel . 'Content/ShoppingBasket.php',
        $librarySubModelContent . '\InstallmentCalculation' => $librarySubModel . 'Content/InstallmentCalculation.php',
        $librarySubModelContent . '\ShoppingBasket\Discount'=> $librarySubModel . 'Content/ShoppingBasket/Discount.php',
        $librarySubModelContent . '\ShoppingBasket\Items'   => $librarySubModel . 'Content/ShoppingBasket/Items.php',
        $librarySubModelContent . '\ShoppingBasket\Shipping'=> $librarySubModel . 'Content/ShoppingBasket/Shipping.php',
        $librarySubModelContent . '\ShoppingBasket\Items\Item'   => $librarySubModel . 'Content/ShoppingBasket/Items/Item.php',
        $librarySubModelContent . '\Payment\InstallmentDetails'  => $librarySubModel . 'Content/Payment/InstallmentDetails.php',
        $librarySubModelContent . '\InstallmentCalculation\CalculationRate'  => $librarySubModel . 'Content/InstallmentCalculation/CalculationRate.php',
        $librarySubModelContent . '\InstallmentCalculation\CalculationTime'  => $librarySubModel . 'Content/InstallmentCalculation/CalculationTime.php',
        $librarySubModelContent . '\InstallmentCalculation\Configuration'    => $librarySubModel . 'Content/InstallmentCalculation/Configuration.php',
        $librarySubModelContent . '\Customer\Addresses'         => $librarySubModel . 'Content/Customer/Addresses.php',
        $librarySubModelContent . '\Customer\BankAccount'       => $librarySubModel . 'Content/Customer/BankAccount.php',
        $librarySubModelContent . '\Customer\Contacts'          => $librarySubModel . 'Content/Customer/Contacts.php',
        $librarySubModelContent . '\Customer\Contacts\Phone'    => $librarySubModel . 'Content/Customer/Contacts/Phone.php',
        $librarySubModelContent . '\Customer\Addresses\Address' => $librarySubModel . 'Content/Customer/Addresses/Address.php',
        $librarySubModelContent . '\Customer'                   => $librarySubModel . 'Content/Customer.php',
        'RatePAY\Model\Request\TraitRequestContent'             => $libraryModel . 'Request/TraitRequestContent.php',
        'RatePAY\Model\Request\TraitRequestSubtype'             => $libraryModel . 'Request/TraitRequestSubtype.php'

    ),
    'events' => array(
        'onActivate'                => 'pi_ratepay_events::onActivate',
        'onDeactivate'              => 'pi_ratepay_events::onDeactivate',
    ),
    'blocks' => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_errors',
            'file'     => 'payment_pi_ratepay_error_dfp.tpl'
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'payment_pi_ratepay_rechnung.tpl'
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'payment_pi_ratepay_rate.tpl'
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'payment_pi_ratepay_elv.tpl'
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_main',
            'file'     => 'order_pi_ratepay_waitingwheel.tpl'
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block'    => 'shippingAndPayment',
            'file'     => 'order_pi_ratepay_rate.tpl'
        ),
        array(
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_form',
            'file'     => 'admin_pi_ratepay_module_config_form.tpl',
        ),
        array(
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_select',
            'file'     => 'admin_pi_ratepay_module_config_var_type_select.tpl',
        ),
    ),
    'settings' => array(
        // ratepay general
        array('group' => 'PI_RATEPAY_GENERAL', 'name' => 'blRPLogging', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GENERAL', 'name' => 'blRPAutoPaymentConfirm', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GENERAL', 'name' => 'sRPDeviceFingerprintSnippetId', 'type' => 'str', 'value' => ''),
        // ratepay germany invoice
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPInvoiceActive', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPInvoiceSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPInvoiceProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPInvoiceSecret', 'type' => 'str', 'value' => ''),
        // ratepay germany installment
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPInstallmentActive', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPInstallmentSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPInstallmentProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPInstallmentSecret', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPInstallmentSettlement', 'type' => 'select', 'value' => 'debit', 'constrains' => 'debit|banktransfer|both'),
        // ratepay germany elv
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPElvActive', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPElvSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'blRPElvIbanOnly', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPElvProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_GERMANY', 'name' => 'sRPElvSecret', 'type' => 'str', 'value' => ''),
        // ratepay austria invoice
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaInvoice', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaInvoiceSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaInvoiceProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaInvoiceSecret', 'type' => 'str', 'value' => ''),
        // ratepay austria installment
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaInstallment', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaInstallmentSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaInstallmentProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaInstallmentSecret', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaInstallmentSettlement', 'type' => 'select', 'value' => 'debit', 'constrains' => 'debit|banktransfer|both'),
        // ratepay austria elv
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaElv', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'blRPAustriaElvSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaElvProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_AUSTRIA', 'name' => 'sRPAustriaElvSecret', 'type' => 'str', 'value' => ''),
        // ratepay switzerland invoice
        array('group' => 'PI_RATEPAY_SWITZERLAND', 'name' => 'blRPSwitzerlandInvoice', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_SWITZERLAND', 'name' => 'blRPSwitzerlandInvoiceSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_SWITZERLAND', 'name' => 'sRPSwitzerlandInvoiceProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_SWITZERLAND', 'name' => 'sRPSwitzerlandInvoiceSecret', 'type' => 'str', 'value' => ''),
        // ratepay netherland invoice
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'blRPNetherlandInvoice', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'blRPNetherlandInvoiceSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'sRPNetherlandInvoiceProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'sRPNetherlandInvoiceSecret', 'type' => 'str', 'value' => ''),
        // ratepay netherland elv
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'blRPNetherlandElv', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'blRPNetherlandElvSandbox', 'type' => 'bool', 'value' => false),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'sRPNetherlandElvProfileId', 'type' => 'str', 'value' => ''),
        array('group' => 'PI_RATEPAY_NETHERLAND', 'name' => 'sRPNetherlandElvSecret', 'type' => 'str', 'value' => ''),
    ),
);
