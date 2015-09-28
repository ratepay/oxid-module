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
 * Helper Class to generate RatePAY order data.
 */
class pi_ratepay_DetailsViewData
{
    /**
     * oxid of order
     * @var string
     */
    private $_orderId;

    /**
     * Name of order details table
     * @var string
     */
    private $pi_ratepay_order_details = 'pi_ratepay_order_details';

    /**
     * Class constructor
     * @param string $orderId oxid of order
     */
    public function __construct($orderId)
    {
        $this->_orderId = $orderId;
    }

    /**
     * Gets all articles with additional informations
     *
     * @return array
     */
    public function getPreparedOrderArticles()
    {
        $orderId = $this->_orderId;

        $articleList = array();

        # Get order articles
        $articlesSql = "SELECT oa.oxid,oa.oxartid,oa.oxartnum,oa.oxvat,oa.oxbprice,oa.oxnprice,oa.oxtitle, oa.oxbrutprice,oa.oxamount, prrod.ordered, prrod.cancelled, prrod.returned, prrod.shipped, if(oa.OXSELVARIANT != '',concat(oa.oxtitle,', ',oa.OXSELVARIANT),oa.oxtitle) as title
				FROM `oxorderarticles` oa, $this->pi_ratepay_order_details prrod
				WHERE prrod.order_number = '$orderId'
				AND prrod.order_number = oa.oxorderid
				AND oa.oxartid = prrod.article_number";
        $articlesResult = mysql_query($articlesSql);
        $i = 0;

        while ($articlesValues = mysql_fetch_object($articlesResult)) {
            $articleList[$i]['oxid'] = $articlesValues->oxid;
            $articleList[$i]['artid'] = $articlesValues->oxartid;
            $articleList[$i]['arthash'] = md5($articlesValues->oxartid);
            $articleList[$i]['artnum'] = $articlesValues->oxartnum;
            $articleList[$i]['title'] = $articlesValues->title;
            $articleList[$i]['oxtitle'] = $articlesValues->oxtitle;
            $articleList[$i]['vat'] = $articlesValues->oxvat;
            $articleList[$i]['unitprice'] = (float) $articlesValues->oxbprice;
            $articleList[$i]['unitPriceNetto'] = (float) $articlesValues->oxnprice;
            $articleList[$i]['amount'] = $articlesValues->ordered - $articlesValues->shipped - $articlesValues->cancelled;
            $articleList[$i]['ordered'] = $articlesValues->ordered;
            $articleList[$i]['shipped'] = $articlesValues->shipped;
            $articleList[$i]['returned'] = $articlesValues->returned;
            $articleList[$i]['cancelled'] = $articlesValues->cancelled;

            if (($articlesValues->ordered - $articlesValues->returned - $articlesValues->cancelled) > 0) {
                $articleList[$i]['totalprice'] = (float) $articlesValues->oxbrutprice;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        $orderSql = "SELECT * from `oxorder` where oxid='$orderId'";

        $orderResult = mysql_query($orderSql);
        $orderValues = mysql_fetch_object($orderResult);


        $rpOrderDetailsWrappingSql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='$orderId' and article_number='oxwrapping'";
        $rpOrderDetailsWrappingResult = mysql_query($rpOrderDetailsWrappingSql);
        $rpOrderDetailsWrappingValues = mysql_fetch_object($rpOrderDetailsWrappingResult);

        if ($rpOrderDetailsWrappingValues->PRICE > 0) {

            $articleList[$i]['oxid']      = "";
            $articleList[$i]['artid']     = "oxwrapping";
            $articleList[$i]['arthash']   = md5("oxwrapping");
            $articleList[$i]['artnum']    = "oxwrapping";
            $articleList[$i]['title']     = "Wrapping Cost";
            $articleList[$i]['oxtitle']   = "Wrapping Cost";
            $articleList[$i]['vat']       = (float) $rpOrderDetailsWrappingValues->VAT;
            $articleList[$i]['unitprice'] = (float) $rpOrderDetailsWrappingValues->PRICE;
            $articleList[$i]['amount']    = 1 - $rpOrderDetailsWrappingValues->SHIPPED - $rpOrderDetailsWrappingValues->CANCELLED;
            $articleList[$i]['ordered']   = $rpOrderDetailsWrappingValues->ORDERED;
            $articleList[$i]['shipped']   = $rpOrderDetailsWrappingValues->SHIPPED;
            $articleList[$i]['returned']  = $rpOrderDetailsWrappingValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsWrappingValues->CANCELLED;

            if (($rpOrderDetailsWrappingValues->ORDERED - $rpOrderDetailsWrappingValues->RETURNED - $rpOrderDetailsWrappingValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $rpOrderDetailsWrappingValues->PRICE;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        $rpOrderDetailsGiftcardsSql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='" . $orderId . "' and article_number='oxgiftcard'";
        $rpOrderDetailsGiftcardsResult = mysql_query($rpOrderDetailsGiftcardsSql);
        $rpOrderDetailsGiftcardsValues = mysql_fetch_object($rpOrderDetailsGiftcardsResult);

        if ($rpOrderDetailsGiftcardsValues->PRICE > 0) {

            $articleList[$i]['oxid'] = "";
            $articleList[$i]['artid'] = "oxgiftcard";
            $articleList[$i]['arthash'] = md5($orderValues->oxartid);
            $articleList[$i]['artnum'] = "oxgiftcard";
            $articleList[$i]['title'] = "Giftcard Cost";
            $articleList[$i]['oxtitle'] = "Giftcard Cost";
            $articleList[$i]['vat'] = (float) $rpOrderDetailsGiftcardsValues->VAT;
            $articleList[$i]['unitprice'] = (float) $rpOrderDetailsGiftcardsValues->PRICE;
            $articleList[$i]['amount'] = 1 - $rpOrderDetailsGiftcardsValues->SHIPPED - $rpOrderDetailsGiftcardsValues->CANCELLED;
            $articleList[$i]['ordered'] = $rpOrderDetailsGiftcardsValues->ORDERED;
            $articleList[$i]['shipped'] = $rpOrderDetailsGiftcardsValues->SHIPPED;
            $articleList[$i]['returned'] = $rpOrderDetailsGiftcardsValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsGiftcardsValues->CANCELLED;

            if (($rpOrderDetailsGiftcardsValues->ORDERED - $rpOrderDetailsGiftcardsValues->RETURNED - $rpOrderDetailsGiftcardsValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $rpOrderDetailsGiftcardsValues->PRICE;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }


        $rpOrderDetailsPaymentSql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='$orderId' and article_number='oxpayment'";
        $rpOrderDetailsPaymentResult = mysql_query($rpOrderDetailsPaymentSql);
        $rpOrderDetailsPaymentValues = mysql_fetch_object($rpOrderDetailsPaymentResult);

        if ($rpOrderDetailsPaymentValues->PRICE > 0) {

            $articleList[$i]['oxid'] = "";
            $articleList[$i]['artid'] = "oxpayment";
            $articleList[$i]['arthash'] = md5($orderValues->oxartid);
            $articleList[$i]['artnum'] = "oxpayment";
            $articleList[$i]['title'] = "Payment Cost";
            $articleList[$i]['oxtitle'] = "Payment Cost";
            $articleList[$i]['vat'] = (float) $rpOrderDetailsPaymentValues->VAT;
            $articleList[$i]['unitprice'] = (float) $rpOrderDetailsPaymentValues->PRICE;
            $articleList[$i]['amount'] = 1 - $rpOrderDetailsPaymentValues->SHIPPED - $rpOrderDetailsPaymentValues->CANCELLED;
            $articleList[$i]['ordered'] = $rpOrderDetailsPaymentValues->ORDERED;
            $articleList[$i]['shipped'] = $rpOrderDetailsPaymentValues->SHIPPED;
            $articleList[$i]['returned'] = $rpOrderDetailsPaymentValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsPaymentValues->CANCELLED;

            if (($rpOrderDetailsPaymentValues->ORDERED - $rpOrderDetailsPaymentValues->RETURNED - $rpOrderDetailsPaymentValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $rpOrderDetailsPaymentValues->PRICE;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        if ($orderValues->OXDISCOUNT != 0) {
            $rpOrderDetailsDiscountsSql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='$orderId' and article_number='Discount'";
            $rpOrderDetailsDiscountsResult = mysql_query($rpOrderDetailsDiscountsSql);
            $rpOrderDetailsDiscountsValues = mysql_fetch_object($rpOrderDetailsDiscountsResult);

            $articleList[$i]['oxid'] = "";
            $articleList[$i]['artid'] = "Discount";
            $articleList[$i]['arthash'] = md5($orderValues->oxartid);
            $articleList[$i]['artnum'] = "Discount";
            $articleList[$i]['title'] = "Discount";
            $articleList[$i]['oxtitle'] = "Discount";
            $articleList[$i]['vat'] = "0";
            $articleList[$i]['unitprice'] = (float) $orderValues->OXDISCOUNT * -1;
            $articleList[$i]['unitPriceNetto'] = (float) $orderValues->OXDISCOUNT * -1;
            $articleList[$i]['amount'] = 1 - $rpOrderDetailsDiscountsValues->SHIPPED - $rpOrderDetailsDiscountsValues->CANCELLED;
            $articleList[$i]['ordered'] = $rpOrderDetailsDiscountsValues->ORDERED;
            $articleList[$i]['shipped'] = $rpOrderDetailsDiscountsValues->SHIPPED;
            $articleList[$i]['returned'] = $rpOrderDetailsDiscountsValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsDiscountsValues->CANCELLED;

            if (($rpOrderDetailsDiscountsValues->ORDERED - $rpOrderDetailsDiscountsValues->RETURNED - $rpOrderDetailsDiscountsValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $orderValues->OXDISCOUNT * -1;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        $rpOrderDetailsDeliverySql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='$orderId' and article_number='oxdelivery'";
        $rpOrderDetailsDeliveryResult = mysql_query($rpOrderDetailsDeliverySql);
        $rpOrderDetailsDeliveryValues = mysql_fetch_object($rpOrderDetailsDeliveryResult);

        if ($rpOrderDetailsDeliveryValues->PRICE > 0) {

            $articleList[$i]['oxid']      = "";
            $articleList[$i]['artid']     = "oxdelivery";
            $articleList[$i]['arthash']   = md5('oxdelivery');
            $articleList[$i]['artnum']    = "oxdelivery";
            $articleList[$i]['title']     = "Delivery Cost";
            $articleList[$i]['oxtitle']   = "Delivery Cost";
            $articleList[$i]['vat']       = (float) $rpOrderDetailsDeliveryValues->VAT;
            $articleList[$i]['unitprice'] = (float) $rpOrderDetailsDeliveryValues->PRICE;
            $articleList[$i]['amount']    = 1 - $rpOrderDetailsDeliveryValues->SHIPPED - $rpOrderDetailsDeliveryValues->CANCELLED;
            $articleList[$i]['ordered']   = $rpOrderDetailsDeliveryValues->ORDERED;
            $articleList[$i]['shipped']   = $rpOrderDetailsDeliveryValues->SHIPPED;
            $articleList[$i]['returned']  = $rpOrderDetailsDeliveryValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsDeliveryValues->CANCELLED;

            if (($rpOrderDetailsDeliveryValues->ORDERED - $rpOrderDetailsDeliveryValues->RETURNED - $rpOrderDetailsDeliveryValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $rpOrderDetailsDeliveryValues->PRICE;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }


        $rpOrderDetailsProtectionSql = "SELECT * from `$this->pi_ratepay_order_details` where order_number='$orderId' and article_number='oxtsprotection'";
        $rpOrderDetailsProtectionResult = mysql_query($rpOrderDetailsProtectionSql);
        $rpOrderDetailsProtectionValues = mysql_fetch_object($rpOrderDetailsProtectionResult);

        if ($rpOrderDetailsProtectionValues->PRICE > 0) {

            $articleList[$i]['oxid'] = "";
            $articleList[$i]['artid'] = "oxtsprotection";
            $articleList[$i]['arthash'] = md5('oxtsprotection');
            $articleList[$i]['artnum'] = "oxtsprotection";
            $articleList[$i]['title'] = "TS Protection Cost";
            $articleList[$i]['oxtitle'] = "TS Protection Cost";
            $articleList[$i]['vat'] = (float) $rpOrderDetailsProtectionValues->VAT;
            $articleList[$i]['unitprice'] = (float) $rpOrderDetailsProtectionValues->PRICE;
            $articleList[$i]['amount'] = 1 - $rpOrderDetailsProtectionValues->SHIPPED - $rpOrderDetailsProtectionValues->CANCELLED;
            $articleList[$i]['ordered'] = $rpOrderDetailsProtectionValues->ORDERED;
            $articleList[$i]['shipped'] = $rpOrderDetailsProtectionValues->SHIPPED;
            $articleList[$i]['returned'] = $rpOrderDetailsProtectionValues->RETURNED;
            $articleList[$i]['cancelled'] = $rpOrderDetailsProtectionValues->CANCELLED;

            if (($rpOrderDetailsProtectionValues->ORDERED - $rpOrderDetailsProtectionValues->RETURNED - $rpOrderDetailsProtectionValues->CANCELLED) > 0) {
                $articleList[$i]['totalprice'] = (float) $rpOrderDetailsProtectionValues->PRICE;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        $vouchersSql = "SELECT ov.oxdiscount AS price, prrod.article_number AS artnr, ov.oxvouchernr AS title, prrod.ordered, prrod.cancelled, prrod.returned, prrod.shipped, ovs.OXSERIENR as seriesTitle, ovs.OXSERIEDESCRIPTION as seriesDescription
		FROM `oxvouchers` ov, " . $this->pi_ratepay_order_details . " prrod, oxvoucherseries ovs
		WHERE prrod.order_number = '" . $orderId . "'
		AND ov.oxorderid = prrod.order_number
		AND prrod.article_number = ov.oxid
        AND ovs.oxid = ov.OXVOUCHERSERIEID";

        $vouchersResult = mysql_query($vouchersSql);

        while ($vouchersValues = mysql_fetch_object($vouchersResult)) {
            $articleList[$i]['oxid'] = "";
            $articleList[$i]['artid'] = $vouchersValues->artnr;
            $articleList[$i]['arthash'] = md5($vouchersValues->artnr);
            $articleList[$i]['artnum'] = $vouchersValues->title;
            $articleList[$i]['title'] = $vouchersValues->seriesTitle;
            $articleList[$i]['oxtitle'] = $vouchersValues->seriesTitle;
            $articleList[$i]['vat'] = "0";
            $articleList[$i]['unitprice'] = "-" . (float) $vouchersValues->price;
            $articleList[$i]['unitPriceNetto'] = "-" . (float) $vouchersValues->price;
            $articleList[$i]['amount'] = 1 - $vouchersValues->shipped - $vouchersValues->cancelled;
            $articleList[$i]['ordered'] = $vouchersValues->ordered;
            $articleList[$i]['shipped'] = $vouchersValues->shipped;
            $articleList[$i]['returned'] = $vouchersValues->returned;
            $articleList[$i]['cancelled'] = $vouchersValues->cancelled;

            if (($vouchersValues->ordered - $vouchersValues->returned - $vouchersValues->cancelled) > 0) {
                $articleList[$i]['totalprice'] = (float) $vouchersValues->price * -1;
            } else {
                $articleList[$i]['totalprice'] = 0;
            }

            $i++;
        }

        return $articleList;
    }
}
