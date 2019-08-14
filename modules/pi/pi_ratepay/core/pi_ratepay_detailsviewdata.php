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
class pi_ratepay_DetailsViewData extends oxBase
{
    /**
     * oxid of order
     * @var string
     */
    private $_orderId;

    /**
     * Order values
     * @var array|null 
     */
    protected $_orderValues = null;

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
        $articleList = $this->_piGetOrderArticleList();
        $articleList = $this->_piAddSpecialCosts($articleList, 'oxwrapping', 'Wrapping Cost');
        $articleList = $this->_piAddSpecialCosts($articleList, 'oxgiftcard', 'Giftcard Cost');
        $articleList = $this->_piAddSpecialCosts($articleList, 'oxpayment', 'Payment Costs');
        $articleList = $this->_piAddSpecialCosts($articleList, 'oxdelivery', 'Delivery Costs');
        $articleList = $this->_piAddSpecialCosts($articleList, 'oxtsprotection', 'TS Protection Cost');
        $articleList = $this->_piAddDiscounts($articleList);
        $articleList = $this->_piAddVouchers($articleList);
        $articleList = $this->_piAddCredit($articleList);

        return $articleList;
    }

    /**
     * Initial method for generating an article list
     *
     * @return array
     */
    protected function _piGetOrderArticleList()
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
        $articleList = array();

        # Get order articles
        $articlesSql = "SELECT
              oo.OXCURRENCY,
              oa.OXID,
              oa.OXARTID,
              oa.OXARTNUM,
              oa.OXVAT,
              oa.OXBPRICE,
              oa.OXNPRICE,
              oa.OXTITLE,
              oa.OXNETPRICE,
              oa.OXAMOUNT,
              prrod.ORDERED,
              prrod.CANCELLED,
              prrod.RETURNED,
              prrod.SHIPPED,
              prrod.UNIQUE_ARTICLE_NUMBER,
              if(oa.OXSELVARIANT != '',concat(oa.OXTITLE,', ',oa.OXSELVARIANT),oa.OXTITLE) as TITLE
            FROM
              `oxorder` oo,
              `oxorderarticles` oa,
              ".$this->pi_ratepay_order_details." prrod
            WHERE
              prrod.order_number = '{$this->_orderId}'
              AND prrod.order_number = oa.oxorderid
              AND oa.oxartid = prrod.article_number
              AND oo.oxid = prrod.order_number";
        $aRows = $oDb->getAll($articlesSql);

        foreach ($aRows as $aRow) {
            $iAmount = $aRow['ORDERED'] - $aRow['SHIPPED'] - $aRow['CANCELLED'];
            $dTotal = $aRow['ORDERED'] - $aRow['RETURNED'] - $aRow['CANCELLED'];

            $listEntry['oxid'] = $aRow['OXID'];
            $listEntry['artid'] = $aRow['OXARTID'];
            $listEntry['arthash'] = md5($aRow['OXARTID']);
            $listEntry['artnum'] = $aRow['OXARTNUM'];
            $listEntry['title'] = $aRow['TITLE'];
            $listEntry['oxtitle'] = $aRow['OXTITLE'];
            $listEntry['vat'] = $aRow['OXVAT'];
            $listEntry['unitprice'] = (float) $aRow['OXNPRICE'];
            $listEntry['amount'] = $iAmount;
            $listEntry['ordered'] = $aRow['ORDERED'];
            $listEntry['shipped'] = $aRow['SHIPPED'];
            $listEntry['returned'] = $aRow['RETURNED'];
            $listEntry['cancelled'] = $aRow['CANCELLED'];
            $listEntry['currency'] = $aRow['OXCURRENCY'];
            $listEntry['bruttoprice'] = (float) $aRow['OXBPRICE'];
            $listEntry['unique_article_number'] = $aRow['UNIQUE_ARTICLE_NUMBER'];

            if ($dTotal > 0) {
                $listEntry['totalprice'] = (float)
                    $aRow['OXBPRICE'] * (
                        $listEntry['ordered'] - 
                        $listEntry['returned'] - 
                        $listEntry['cancelled']
                    );
            } else {
                $listEntry['totalprice'] = 0;
            }

            $articleList[] = $listEntry;
        }

        return $articleList;
    }

    /**
     * Adding special costs to article list
     *
     * @param $articleList
     * @param $ident
     * @param $title
     * @return array
     */
    protected function _piAddSpecialCosts($articleList, $ident, $title)
    {
        $orderValues = $this->_piGetOrderValues();
        $aRow = $this->_piGetOrderSpecialCostsQuery($ident);

        if ($aRow['PRICE'] > 0) {
            $listEntry['oxid'] = "";
            $listEntry['artid'] = $ident;
            $listEntry['arthash'] = md5($orderValues['OXARTID']);
            $listEntry['artnum'] = $ident;
            $listEntry['title'] = $title;
            $listEntry['oxtitle'] = $title;
            $listEntry['vat'] = (float) $aRow['VAT'];
            $listEntry['unitprice'] = (float) $aRow['PRICE'];
            $listEntry['amount'] = 1 - $aRow['SHIPPED'] - $aRow['CANCELLED'];
            $listEntry['ordered'] = $aRow['ORDERED'];
            $listEntry['shipped'] = $aRow['SHIPPED'];
            $listEntry['returned'] = $aRow['RETURNED'];
            $listEntry['cancelled'] = $aRow['CANCELLED'];
            $listEntry['currency'] = $aRow['oxcurrency'];
            $listEntry['unique_article_number'] = $aRow['unique_article_number'];

            $blHasTotal = (
                ($aRow['ORDERED'] - $aRow['RETURNED'] - $aRow['CANCELLED']) > 0
            );

            if ($blHasTotal) {
                $dTotal =
                    (float) $aRow['PRICE'] +
                    ((float) $aRow['PRICE'] *
                        round((float) $aRow['VAT']) / 100);

                $listEntry['totalprice'] = $dTotal;
            } else {
                $listEntry['totalprice'] = 0;
            }

            $articleList[] = $listEntry;
        }

        return $articleList;
    }

    /**
     * Add discounts to article list
     * 
     * @param $articleList
     * @return array
     */
    protected function _piAddDiscounts($articleList)
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sQuery = "
            SELECT
                oo.oxcurrency,
                od.oxid AS ARTID,
                od.oxtitle AS TITLE,
                oo.oxdiscount AS PRICE,
                prrod.ordered AS ORDERED,
                prrod.cancelled AS CANCELLED,
                prrod.returned AS RETURNED,
                prrod.shipped AS SHIPPED,
                prrod.unique_article_number
            FROM
                `oxorder` oo,
                `oxdiscount` od,
                " . $this->pi_ratepay_order_details . " prrod
            WHERE
                prrod.order_number = '" . $this->_orderId . "'
                AND prrod.article_number = od.oxid
                AND oo.oxid = prrod.order_number";

        $aRow = $oDb->getRow($sQuery);

        if ($aRow['PRICE'] != 0) {

            $listEntry['oxid'] = "";
            $listEntry['artid'] = $aRow['ARTID'];
            $listEntry['arthash'] = md5($aRow['oxartid']);
            $listEntry['artnum'] = "discount";
            $listEntry['title'] = $aRow['TITLE'];
            $listEntry['oxtitle'] = $aRow['TITLE'];
            $listEntry['vat'] = "0";
            $listEntry['unitprice'] = (float) $aRow['PRICE'];
            $listEntry['amount'] = 1 - $aRow['SHIPPED'] - $aRow['CANCELLED'];
            $listEntry['ordered'] = $aRow['ORDERED'];
            $listEntry['shipped'] = $aRow['SHIPPED'];
            $listEntry['returned'] = $aRow['RETURNED'];
            $listEntry['cancelled'] = $aRow['CANCELLED'];
            $listEntry['currency'] = $aRow['oxcurrency'];
            $listEntry['unique_article_number'] = $aRow['unique_article_number'];

            $blHasTotal = (
                ($aRow['ORDERED'] - $aRow['RETURNED'] - $aRow['CANCELLED']) > 0
            );

            if ($blHasTotal) {
                $dTotal =
                    (float) $aRow['PRICE'] +
                    ((float) $aRow['PRICE'] *
                        round((float) $aRow['VAT']) / 100);

                $listEntry['totalprice'] = $dTotal;
            } else {
                $listEntry['totalprice'] = 0;
            }

            $articleList[] = $listEntry;
        }

        return $articleList;
    }

    /**
     * Add vouchers to article list
     *
     * @param $articleList
     * @return array
     */
    protected function _piAddVouchers($articleList) 
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sQuery = "
            SELECT
                oo.oxcurrency,
                ov.oxdiscount AS price,
                oo.OXVOUCHERDISCOUNT as totaldiscount,
                prrod.article_number AS artnr,
                ov.oxvouchernr AS title,
                prrod.ORDERED,
                prrod.CANCELLED,
                prrod.RETURNED,
                prrod.SHIPPED,
                prrod.unique_article_number,
                ovs.OXSERIENR as seriesTitle,
                ovs.OXSERIEDESCRIPTION as seriesDescription
            FROM
                `oxorder` oo,
                `oxvouchers` ov,
                " . $this->pi_ratepay_order_details . " prrod,
                oxvoucherseries ovs
            WHERE
                prrod.order_number = '" . $this->_orderId . "' AND 
                ov.oxorderid = prrod.order_number AND 
                prrod.article_number = ov.oxid AND 
                ovs.oxid = ov.OXVOUCHERSERIEID AND 
                oo.oxid = prrod.order_number";

        $aRows = $oDb->getAll($sQuery);

        $dSum = 0;
        for ($i = 0; $i < count($aRows); $i++) {
            $aRow = $aRows[$i];
            if ($aRow['price'] != 0) {
                $listEntry['oxid'] = "";
                $listEntry['artid'] = $aRow['artnr'];
                $listEntry['arthash'] = md5($aRow['artnr']);
                $listEntry['artnum'] = 'voucher_' . $aRow['title'];
                $listEntry['title'] = $aRow['seriesTitle'];
                $listEntry['oxtitle'] = $aRow['seriesTitle'];
                $listEntry['vat'] = "0";
                $listEntry['unitprice'] = (float)$aRow['price'];
                $listEntry['amount'] = 1 - $aRow['SHIPPED'] - $aRow['CANCELLED'];
                $listEntry['ordered'] = $aRow['ORDERED'];
                $listEntry['shipped'] = $aRow['SHIPPED'];
                $listEntry['returned'] = $aRow['RETURNED'];
                $listEntry['cancelled'] = $aRow['CANCELLED'];
                $listEntry['currency'] = $aRow['oxcurrency'];
                $listEntry['unique_article_number'] = $aRow['unique_article_number'];

                $blHasTotal = (
                    ($aRow['ORDERED'] - $aRow['RETURNED'] - $aRow['CANCELLED']) > 0
                );

                if ($blHasTotal) {
                    $dTotal =
                        (float)$aRow['price'] +
                        ((float)$aRow['price'] *
                            round((float)$aRow['VAT']) / 100);

                    $listEntry['totalprice'] = $dTotal;
                } else {
                    $listEntry['totalprice'] = 0;
                }

                $dSum += (float)$aRow['price'];

                if ($blHasTotal && count($aRows) == ($i + 1) && $dSum != (float)$aRow['totaldiscount']) { // is last voucher
                    // compensation for rounding discrepancies
                    $dDiff = (float)$aRow['totaldiscount'] - $dSum;
                    $listEntry['unitprice'] += $dDiff;
                    $listEntry['totalprice'] += $dDiff;
                }

                $articleList[] = $listEntry;
            }
        }

        return $articleList;
    }

    /**
     * Add credit to articlelist
     *
     * @param $articleList
     * @return array
     */
    protected function _piAddCredit($articleList)
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sQuery = "
            SELECT
                oo.oxcurrency,
                ov.oxdiscount AS price,
                prrod.article_number AS artnr,
                ov.oxvouchernr AS title,
                prrod.ORDERED,
                prrod.CANCELLED,
                prrod.RETURNED,
                prrod.unique_article_number,
                prrod.SHIPPED
            FROM
                `oxorder` oo,
                `oxvouchers` ov,
                " . $this->pi_ratepay_order_details . " prrod
            WHERE
            prrod.order_number = '" . $this->_orderId . "'
            AND ov.oxorderid = prrod.order_number
            AND ov.oxvoucherserieid = 'Anbieter Gutschrift'
            AND prrod.article_number = ov.oxid
            AND oo.oxid = prrod.order_number";

        $aRows = $oDb->getAll($sQuery);
        foreach ($aRows as $aRow) {
            if ($aRow['price'] != 0) {
                $listEntry['oxid'] = "";
                $listEntry['artid'] = $aRow['artnr'];
                $listEntry['arthash'] = md5($aRow['artnr']);
                $listEntry['artnum'] = 'voucher_' . $aRow['title'];
                $listEntry['title'] = $aRow['seriesTitle'];
                $listEntry['oxtitle'] = $aRow['seriesTitle'];
                $listEntry['vat'] = "0";
                $listEntry['unitprice'] = (float)$aRow['price'];
                $listEntry['amount'] = 1 - $aRow['SHIPPED'] - $aRow['CANCELLED'];
                $listEntry['ordered'] = $aRow['ORDERED'];
                $listEntry['shipped'] = $aRow['SHIPPED'];
                $listEntry['returned'] = $aRow['RETURNED'];
                $listEntry['cancelled'] = $aRow['CANCELLED'];
                $listEntry['currency'] = $aRow['oxcurrency'];
                $listEntry['unique_article_number'] = $aRow['unique_article_number'];

                $blHasTotal = (
                    ($aRow['ORDERED'] - $aRow['RETURNED'] - $aRow['CANCELLED']) > 0
                );

                if ($blHasTotal) {
                    $dTotal =
                        (float)$aRow['price'] +
                        ((float)$aRow['price'] *
                            round((float)$aRow['VAT']) / 100);

                    $listEntry['totalprice'] = $dTotal;
                } else {
                    $listEntry['totalprice'] = 0;
                }

                $articleList[] = $listEntry;
            }
        }

        return $articleList;
    }

    /**
     * Returns special costs
     *
     * @param $sIdent
     * @return mixed
     */
    protected function _piGetOrderSpecialCostsQuery($sIdent)
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sQuery = "
            SELECT 
                oo.oxcurrency, 
                prrod.* 
            FROM 
                {$this->pi_ratepay_order_details} prrod, oxorder oo
            WHERE
                prrod.order_number = '{$this->_orderId}' AND 
                prrod.article_number = '{$sIdent}' AND 
                oo.oxid = prrod.order_number";

        $aRow = $oDb->getRow($sQuery);

        return $aRow;
    }

    /**
     * Returns order informations
     * 
     * @return array|null
     */
    protected function _piGetOrderValues() 
    {
        if ($this->_orderValues === null) {
            $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
            $orderId = $this->_orderId;
            $orderSql = "SELECT * from `oxorder` where oxid='{$orderId}'";
            $this->_orderValues = $oDb->getAll($orderSql);
        }
        
        return $this->_orderValues;
    }
}
