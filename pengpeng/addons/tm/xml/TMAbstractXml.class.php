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
 * Abstract xml
 *
 * @package lib.xml
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMAbstractXml.class.php 2008-10-24 by ianzhang
 */
abstract class TMAbstractXml implements TMXmlInterface
{
    /**
     * @var DOMDocument
     */
    protected $doc;

    public function __construct($path, $version = '1.0', $encoding = 'utf-8')
    {
    }

    /**
     * The document load xml file
     *
     * @param  boolean $isValidated   是否进行加载时的xml的DTD校验
     *
     */
    public function loadXML($isValidated = true)
    {
    }

    /**
     * Validate the loaded xml, used after loadXML
     *
     * @return boolean $result        true or false
     */
    public function validateXML()
    {
        $this->doc->validate();
    }

    /**
     * save document object to xml file
     *
     */
    public function saveXML()
    {
    }

    /**
     * Return the dom document to user
     *
     * @return DOMDocument $doc    the dom document
     */
    public function getDOMDocument()
    {
        return $this->doc;
    }

    /**
     * Set dom document
     *
     * @param  DOMDocument $doc     param_description
     * @return void
     * @throws TMXmlException
     */
    public function setDOMDocument($doc)
    {
        if(!$doc instanceof DOMDocument)
        {
            $adlog = new TMLog();
            $adlog->lo("在设置DOM Document时,Dom Document的类型不正确");
            throw new TMXmlException(TMConstant::xmlError(TMConstant::XML_ERROR_SET_DOM));
        }
        $this->doc = $doc;
    }

    /**
     * Get Xml string
     *
     * @param  DOMNode $node       the dom node
     *
     * @return string $xmlString   the xml string
     * @throws TMXmlException
     */
    public function getXML($node = null)
    {
        if($node != null && !$node instanceof DOMNode)
        {
            $adlog = new TMLog();
            $adlog->lo("在获取节点XML时,Dom Node的类型不正确");
            throw new TMXmlException(TMConstant::xmlError(TMConstant::XML_ERROR_SET_NODE));
        }
        $xmlString = $this->doc->saveXML ( $node );
        return $xmlString;
    }

    /**
     * Get Xml array string
     *
     * @return array $xmlString    the Xml array string
     */
    public function parseXmlToArray()
    {
    }

    /**
     * convert xml struct to array
     *
     * @param  string $item       the xml node
     * @return array $item    the string array
     *
     */
    protected function convertStructToArray($item)
    {
        if (! is_string ( $item ))
        {
            $item = ( array ) $item;
            foreach ( $item as $key => $val )
            {
                $item [$key] = $this->convertStructToArray ( $val );
            }
        }
        return $item;
    }
}