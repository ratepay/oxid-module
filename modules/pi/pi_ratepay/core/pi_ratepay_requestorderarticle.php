<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Model for order articles
 */
class pi_ratepay_RequestOrderArticle
{

    /**
     * Title
     * @var string
     */
    private $_title;

    /**
     * article number
     * @var string
     */
    private $_articleNumber;

    /**
     * Quantity
     * @var int
     */
    private $_quantity;

    /**
     * Unit price
     * @var double
     */
    private $_unitPrice;

    /**
     * Total price
     * @var double
     */
    private $_price;

    /**
     * VAT percentage
     * @var double
     */
    private $_vatValue;

    /**
     * Class constructor
     *
     * @param string $title
     * @param string $articleNumber
     * @param int $quantity
     * @param double $unitPrice
     * @param double $price
     * @param double $vatValue
     */
    function __construct($title, $articleNumber, $quantity, $unitPrice, $price, $vatValue)
    {
        $this->_title = $title;
        $this->_articleNumber = $articleNumber;
        $this->_quantity = $quantity;
        $this->_unitPrice = $unitPrice;
        $this->_price = $price;
        $this->_vatValue = $vatValue;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Get article number
     * @return string
     */
    public function getArticleNumber()
    {
        return $this->_articleNumber;
    }

    /**
     * Get quantity
     * @return int
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * Get unit price
     * @return double
     */
    public function getUnitPrice()
    {
        return $this->_unitPrice;
    }

    /**
     * Get total price
     * @return double
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * Get vat percentage
     * @return double
     */
    public function getVatValue()
    {
        return $this->_vatValue;
    }

}
