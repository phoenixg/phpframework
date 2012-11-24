<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * Tencent mind xml interface
 *
 * @package lib.xml
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMXmlInterface.class.php 2008-10-23 by ianzhang
 */
interface TMXmlInterface
{
    public function __construct($path, $version = '1.0', $encoding = 'utf-8');

    /**
     * The document load xml
     *
     * @param  boolean $isValidated   是否进行加载时的xml的DTD校验
     * @return boolean $result        true or false
     */
    public function loadXML($isValidated = true);

    /**
     * Validate the loaded xml, used after loadXML
     *
     * @return boolean $result        true or false
     */
    public function validateXML() ;

    /**
     * save document object to xml
     *
     * @param  DOMDocument $doc    the dom document
     * @return $result             the number of bytes written or FALSE if an error occurred
     */
    public function saveXML();

    /**
     * Set dom document
     *
     * @param  DOMDocument $doc     param_description
     * @return void
     */
    public function setDOMDocument($doc);

    /**
     * Return the dom document to user
     *
     * @return DOMDocument $doc    the dom document
     */
    public function getDOMDocument();

    /**
     * Get Xml string
     *
     * @param  DOMNode $node       the dom node
     *
     * @return string $xmlString   the xml string
     */
    public function getXML($node = null);

    /**
     * Get Xml array string
     *
     * @return array $xmlString    the Xml array string
     */
    public function parseXmlToArray();
}