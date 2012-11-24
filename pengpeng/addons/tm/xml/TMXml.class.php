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
 * Tencent mind xml
 *
 * @package lib.xml
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMXml.class.php 2008-10-24 by ianzhang
 */
class TMXml extends TMAbstractXml
{
    private $xmlString;

    /**
     * Construction function
     *
     * @param  string $path     the xml string
     * @param  boolean $isValidated   是否进行加载时的xml的DTD校验
     * @param  string $version  the XML version
     * @param  string $encoding the xml encoding
     * @throws TMXmlException
     */
    public function __construct($path, $isValidated = true, $version = '1.0', $encoding = 'utf-8')
    {
        $this->doc = new DOMDocument ( $version, $encoding );
        $this->xmlString = $path;
        if(!$this->loadXML($isValidated))
        {
            $adlog = new TMLog();
            $adlog->ll("构造TMXml时加载xml失败");
            throw new TMXmlException(TMConstant::xmlError(TMConstant::XML_ERROR_LOAD));
        }
    }

    /**
     * The document load xml file
     *
     * @param  boolean $isValidated   是否进行加载时的xml的DTD校验
     * @return boolean $result        true or false
     */
    public function loadXML($isValidated = true)
    {
        $this->doc->validateOnParse = $isValidated;
        return $this->doc->loadXML($this->xmlString);
    }

    /**
     * save document object to xml file
     *
     * @return void
     * @throws TMXmlException
     */
    public function saveXML()
    {
        $result = $this->doc->saveXML();
        if($result === false)
        {
            $adlog = new TMLog();
            $adlog->ll("保存xml的DOM结构失败");
            throw new TMXmlException(TMConstant::xmlError(TMConstant::XML_ERROR_SAVE_DOM));
        }
        $this->xmlString = $result;
    }

    /**
     * Parse xml to array
     *
     * @return array $array    the xml array
     * @throws TMXmlException
     */
    public function parseXmlToArray()
    {
        $result = simplexml_load_string( $this->xmlString,null,LIBXML_NOCDATA );
        if ($result === false)
        {
            $adlog = new TMLog();
            $adlog->ll("XML parse error");
            throw new TMXmlException(TMConstant::xmlError(TMConstant::XML_ERROR_PARSE_ARRAY));
        }
        $array = ( array ) $result;
        foreach ( $array as $key => $item )
        {
            $array [$key] = $this->convertStructToArray ( ( array ) $item );
        }
        return $array;
    }

    /**
     * Get Xml string
     *
     * @return string $xmlString   the xml string
     */
    public function getXMLString()
    {
        return $this->xmlString;
    }
}