<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Model class for pi_ratepay_logs table
 * @extends oxBase
 */
class pi_ratepay_Logs extends oxBase
{

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'pi_ratepay_logs';

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('pi_ratepay_logs');
    }

    protected function _getFormattedXml($oField)
    {
        $oSimpleXml = simplexml_load_string($oField->rawValue);
        if ($oSimpleXml === false) {
            return $oField->value;
        }

        $dom = dom_import_simplexml($oSimpleXml);
        if (!$dom) {
            return $oField->value;
        }

        $dom = $dom->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }

    public function getFormattedRequest()
    {
        return htmlentities($this->_getFormattedXml($this->pi_ratepay_logs__request));
    }

    public function getFormattedResponse()
    {
        return htmlentities($this->_getFormattedXml($this->pi_ratepay_logs__response));
    }

}
