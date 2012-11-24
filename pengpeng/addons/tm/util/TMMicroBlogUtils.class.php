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
 * TMMicroBlogUtils
 * 微博接口工具类
 *
 * @package projectlib.classes
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMMicroBlogUtils.class.php 2010-11-17 by ianzhang    
 */
class TMMicroBlogUtils {
	/**
	 * 判断是否开通微博，需要登录态
	 * @return boolean
	 */
	public static function queryStatus($uin, $ip, $queryQQs = array()) {
		$request = TMComponent::getInstance()->getRequest();
		if(isset($_ENV["SERVER_TYPE"]) && $_ENV["SERVER_TYPE"] == "test")
		{
			return true;
		}
		
		$socket = new TMSocket();
		$socket->setTimeout(500);
		$socket->connect('10.128.39.13',15101);
		$stx = 0x02;
		
		//head
		$wLength = 1+22+1;
		$wVersion = 0;
		$wCmdCode = 0x4d7;
		$cServiceType = 18;
		$dwSequence = time();
		$dwUin = $uin;
		$dwUserIp = ip2long($ip);
		$cSessionKeyType = 1;
        $acSessionKey = $request->getCookie("skey");
        if(empty($acSessionKey))
        {
        	throw new TMBusinessException("没有登录QQ号码");
        }
        $wSessionLen = strlen($acSessionKey);
        $wLength += $wSessionLen;
		
		//body
		$wUinCount = 1;
		$dwUin0 = $uin;
        $wLength += 6;
        
		$etx = 0x03;
		
		$data = pack("cn3cN3cna".$wSessionLen."nNc"
		  ,$stx,$wLength,$wVersion,$wCmdCode,$cServiceType,$dwSequence,$dwUin,$dwUserIp
		  ,$cSessionKeyType, $wSessionLen, $acSessionKey
		  ,$wUinCount, $dwUin0, $etx);
        
		$result = $socket->sendData($data);
		
		$socket->close();
		
		if(strlen($result) == 18){
            $resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
            /cserviceType/NSequence/NUin/cresult/cETX", $result);
		}else{
			$resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
			/cserviceType/NSequence/NUin/cresult/NnextUin/nfilledCount/Nuin0/cvalue0/cETX", $result);
		}
		
		if($resultUnpack['result'] == 0)
		{
            if($resultUnpack['value0'] == 1)
            {
            	return true;
            }else{
            	return false;
            }
		}else{
			throw new TMBusinessException("系统繁忙");
		}
	}
	
	/**
	 * 发送微博
	 * @param $uin
	 * @param $ip
	 * @param $content
	 * @return $mixed
	 */
	public static function sendMicroBlog($uin, $ip, $content, $from = 3, $dwPubFrom = 1000596)
	{
		$request = TMComponent::getInstance()->getRequest();
	    if(isset($_ENV["SERVER_TYPE"]) && $_ENV["SERVER_TYPE"] == "test")
        {
            return array("tweetId" => 1123123123, "time" => time());
        }
		$socket = new TMSocket();
        $socket->connect('10.128.39.13',15101);
        $stx = 0x02;
        
        //head
        $wLength = 1+22+1;
        $wVersion = 0;
        $wCmdCode = 0x5c0;
        $cServiceType = 0;
        $dwSequence = time();
        $dwUin = $uin;
        $dwUserIp = ip2long($ip);
        $cSessionKeyType = 1;
        $acSessionKey = $request->getCookie("skey");
        if(empty($acSessionKey))
        {
            throw new TMBusinessException("没有登录QQ号码");
        }
        $wSessionLen = strlen($acSessionKey);
        $wLength += $wSessionLen;
        
        //body
        $cType = 1;
        $cContentType = 1;
        $cFrom = $from;
        $cAccessLevel = 0;
        $wContentLen = strlen($content);
        $acContent = $content;
        //$dwPubFrom = 1000596;
        $cResv = "000000000000";
        $cLen = 0;
        $acDescribe = ""; 
        $wLength += (23+$wContentLen);
        
        $etx = 0x03;
        
        $data = pack("cn3cN3cna".$wSessionLen."c4na".$wContentLen."Na12cc"
            ,$stx,$wLength,$wVersion,$wCmdCode,$cServiceType,$dwSequence,$dwUin,$dwUserIp
            ,$cSessionKeyType, $wSessionLen, $acSessionKey
            ,$cType, $cContentType, $cFrom, $cAccessLevel, $wContentLen, $acContent
            ,$dwPubFrom, $cResv, $cLen, $etx);
        
        $result = $socket->sendData($data);
        
        $socket->close();
        
        if(strlen($result) == 18){
            $resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
            /cserviceType/NSequence/NUin/cresult/cETX", $result);
        }else{
            $resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
            /cserviceType/NSequence/NUin/cresult/cbodyResult/H16ddwTweetId/NdwTime/cETX", $result);
        }

        if($resultUnpack['result'] == 0 && $resultUnpack['bodyResult'] == 0)
        {
            return array("tweetId" => hexdec($resultUnpack['ddwTweetId']), "time" => $resultUnpack['dwTime']);
        }else{
            throw new TMBusinessException("您的操作过于频繁，请稍后再试");
        }
	}
	
	public static function getMicroBlogsByUser($uin, $number = 10)
	{
        $curl = new TMCurl();
        $curl->setVHost("t.webdev.com");
        $params = array();
        $params["name"] = $uin;
        $params["num"] = $number;
        $params["from"] = "nikebbn";
        
        if(isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == 'test'){
        	$result = file_get_contents(ROOT_PATH."web/newbroad.xml");
        }else{
            $result = $curl->sendByGet($params, "http://172.27.206.224/xml/user/newbroad");
        }
        
        $xml = new TMXml($result);
        $array = $xml->parseXmlToArray();
        $list = $array["broads"];
        
        return $list;
	}
	
	public static function getMicroBlogsBySubject($subject, $number = 10)
	{
		$curl = new TMCurl();
		$curl->setVHost("t.webdev.com");
        $params = array();
        $params["text"] = $subject;
        $params["num"] = $number;
        $params["from"] = "nikebbn";
        
        if(isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == 'test'){
            $result = file_get_contents(ROOT_PATH."web/broadlist.xml");
        }else{
            $result = $curl->sendByGet($params, "http://172.27.206.224/xml/topic/broadlist");
        }
        
        $xml = new TMXml($result);
        $array = $xml->parseXmlToArray();

        $list = $array["broads"];

        return $list;
	}
	
    public static function getOneMicroBlogInfo($id)
    {        
        $curl = new TMCurl();
        $curl->setVHost("t.webdev.com");
        $params = array();
        $params["msgid"] = $id;
        $params["from"] = "nikebbn";
        
        if(isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == 'test'){
            $result = file_get_contents(ROOT_PATH."web/info.xml");
        }else{
            $result = $curl->sendByGet($params, "http://172.27.206.224/xml/message/info");
        }
        
        $xml = new TMXml($result);
        $array = $xml->parseXmlToArray();
        $info = $array["broads"]["broad"];
        
        return $info;
    }
    
	/**
	 * 发送微博
	 * @param $uin
	 * @param $ip
	 * @param $content
	 * @return $mixed
	 */
	public static function modifyMicroInfo($uin, $ip, $content, $from = 3, $dwPubFrom = 1000596)
	{
		$request = TMComponent::getInstance()->getRequest();
	    if(isset($_ENV["SERVER_TYPE"]) && $_ENV["SERVER_TYPE"] == "test")
        {
            return array("tweetId" => 1123123123, "time" => time());
        }
		$socket = new TMSocket();
        $socket->connect('10.128.39.13',15101);
        $stx = 0x02;
        
        //head
        $wLength = 0;
        $wVersion = 5;
        $wCommand = 0x5ce;
        $dwUin = $uin;
        $cResult='';
        
        $wLength = 1+22+1;
        $wVersion = 0;
        $wCmdCode = 0x5c0;
        $cServiceType = 0;
        $dwSequence = time();
        $dwUin = $uin;
        $dwUserIp = ip2long($ip);
        $cSessionKeyType = 1;
        $acSessionKey = $request->getCookie("skey");
        if(empty($acSessionKey))
        {
            throw new TMBusinessException("没有登录QQ号码");
        }
        $wSessionLen = strlen($acSessionKey);
        $wLength += $wSessionLen;
        
        //body
        $cType = 1;
        $cContentType = 1;
        $cFrom = $from;
        $cAccessLevel = 0;
        $wContentLen = strlen($content);
        $acContent = $content;
        //$dwPubFrom = 1000596;
        $cResv = "000000000000";
        $cLen = 0;
        $acDescribe = ""; 
        $wLength += (23+$wContentLen);
        
        $etx = 0x03;
        
        $data = pack("cn3cN3cna".$wSessionLen."c4na".$wContentLen."Na12cc"
            ,$stx,$wLength,$wVersion,$wCmdCode,$cServiceType,$dwSequence,$dwUin,$dwUserIp
            ,$cSessionKeyType, $wSessionLen, $acSessionKey
            ,$cType, $cContentType, $cFrom, $cAccessLevel, $wContentLen, $acContent
            ,$dwPubFrom, $cResv, $cLen, $etx);
        
        $result = $socket->sendData($data);
        
        $socket->close();
        
        if(strlen($result) == 18){
            $resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
            /cserviceType/NSequence/NUin/cresult/cETX", $result);
        }else{
            $resultUnpack = unpack("cSTX/nwLength/nwVersion/nwCmdCode
            /cserviceType/NSequence/NUin/cresult/cbodyResult/H16ddwTweetId/NdwTime/cETX", $result);
        }

        if($resultUnpack['result'] == 0 && $resultUnpack['bodyResult'] == 0)
        {
            return array("tweetId" => hexdec($resultUnpack['ddwTweetId']), "time" => $resultUnpack['dwTime']);
        }else{
            throw new TMBusinessException("您的操作过于频繁，请稍后再试");
        }
	}
	    
}