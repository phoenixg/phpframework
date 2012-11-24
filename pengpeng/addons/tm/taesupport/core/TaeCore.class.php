<?php
require_once 'TaeConstants.class.php';
require_once 'JSON.class.php';
require_once 'TaeException.class.php';

class TaeCore{
	
	private static $_inited = false;
	private static $_config = array();
	private static $_header = array();
	private static $_headerField = array(TaeConstants::UIN,TaeConstants::USER_IP,TaeConstants::ACT_ID,TaeConstants::VERSION);
	
	public static function taeInit($key,$value)
	{
		self::$_config[$key] = $value;
		//如果是header字段，加入到header数组
		if(in_array($key,self::$_headerField))
		{
			self::$_header[TaeConstants::HEADER_PREFIX.$key] = $value;
		}
	}
	
	public static function taeCall($commmand,$parameter = array())
	{
		//为参数增加body前缀
		$data = array();
		foreach ($parameter as $key => $value)
		{
			$data[TaeConstants::BODY_PREFIX.$key] = $value;
		}
		//加入命令号
		self::$_header[TaeConstants::HEADER_PREFIX."cmd_id"] = $commmand;
		//与header数组进行合并
		$data = array_merge(self::$_header,$data);
		//生成json
		$jsonObj = new JSON();
		$json = $jsonObj->serialize($data);

		//使用http方式调用后台
		$ret = self::getJson($commmand,$json);
		
		//解码
		$result = $jsonObj->unserialize($ret);
		if(empty($result))
		{
			self::throwException("Fail to decode:$ret",TaeConstants::ERR_DECODE_FAIL);
		}
		if($result[TaeConstants::BODY_PREFIX."retcode"]!=0)//调用出错
		{
			self::throwException($result[TaeConstants::BODY_PREFIX."rspmsg"],$result[TaeConstants::BODY_PREFIX."retcode"],$json,$result);
		}

		return self::getBody($result);
	}
	
	public static function getConfig($type)
	{
		return self::$_config[$type];
	}
	
	/**
	 * 取出数组中的body部分。并且去掉body前缀
	 *
	 * @param unknown_type $data
	 * @return unknown
	 */
	private static function getBody($data)
	{
		$result = array();
		foreach ($data as $key => $value)
		{
			if(substr($key,0,5)==TaeConstants::BODY_PREFIX)
			{
				$result[substr($key,5)] = $value;
			}
		}
		return $result;
	}
	
	private function getJson($cmd,$reqJson)
	{
		$server_ip = self::getConfig(TaeConstants::SERVER_IP);
		$server_port = self::getConfig(TaeConstants::SERVER_PORT);
		$server_name = TaeConstants::getCommandServer($cmd);
		$url = "http://$server_ip/$server_name/$cmd";
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_PORT,$server_port);
		curl_setopt($ch,CURLOPT_HEADER,false);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$reqJson);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
	}
	
	private static function throwException($msg,$ret_code,$request='',$response='')
	{
		$exception = new TaeException($msg);
		$exception->setRetCode($ret_code);
		$exception->setRequest($request);
		$exception->setResponse($response);
		throw $exception;
	}
}