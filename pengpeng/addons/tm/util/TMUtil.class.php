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
 * The util class
 *
 * @package lib.util
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUtil.class.php 2008-9-6 by ianzhang
 */
class TMUtil
{
    /**
     * @var array    用于生成随机兑换码
     * @access private
     */
    private static $charSet = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y',
                                            'a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y');
    /*
     * 用于生成随机兑换码的设置函数
     */
    public static  function setCharSet($array) {
            self::$charSet = $array;
    }
    /*
     * 用于生成随机兑换码的取长度函数
     */
    private static function getCharLength($isLowerCase) {
        if($isLowerCase){
            $arrayIndex = count(self::$charSet);
        }else{
            $arrayIndex = 0;
            foreach (self::$charSet as $char) {
                if (($char >= '0' && $char <= '9') || ($char >= 'A' && $char <= 'Z')) {
                    $arrayIndex++;
                }
            }
        }
        $arrayIndex--;
        return $arrayIndex;
    }
    /**
     * Generate the random string
     *
     * @param  int $length                     Code的随机位长度。   The length of generated string
     * @param  string $prefix                  Code的固定位内容。
     * @param  boolean $isLowerCase     是否允许Code中出现小写字母。
     * @return random string
     */
    public static function getRandomString($length,$prefix="",$isLowerCase=false)
    {
        $returnString = $prefix;
        for($i = 0; $i < $length; $i ++)
        {
            //mt_srand((double)microtime()*1000000);
            $arrayIndex = self::getCharLength($isLowerCase);
            $randASC = self::$charSet[mt_rand(0,$arrayIndex)];
            $returnString .=$randASC;
        }
        return $returnString;
    }
    /*
     * 得到当前运行的环境
     */
    public static function getServerType()
    {
        return $_SERVER['SERVER_TYPE'];
    }
    /**
     * Generate the random code, then  write into the files.
     *
     * @param string $path            路径、及文件名前部分
     * @param int $allCount        Code总数量
     * @param int $perFileCount    每个文件的Code存放数量限制
     * @param int $length          Code的随机位长度。
     * @param string $prefix           Code的固定位内容。
     * @param boolean $isLowerCase     是否允许Code中出现小写字母。
     * @return void
     */
    public static function generateRandomCode($path,$allCount,$perFileCount=200000,$length=11,$prefix="",$isLowerCase=false)
    {
        $arrayIndex = self::getCharLength($isLowerCase);

        $filecount = ceil($allCount/$perFileCount);
        $index = 0;
        for($i=1;$i<=$filecount;$i++)
        {
            if($index > $arrayIndex)
            {
                $index = 0;
            }

            $prefix_p = $prefix.self::$charSet[$index];
            if($perFileCount*$i<=$allCount)
            {
                $recordCount = $perFileCount;
            }
            else
            {
                $recordCount = $allCount-$perFileCount*($i-1);
            }

            $string ="";
            $file = new  TMFile($path.$prefix."_".date("Y-m-d")."_".$i.".txt","w+");
            for($j=0;$j<$recordCount;$j++)
            {
                $string .= self::getRandomString($length,$prefix_p)."\n";
            }
            $file->insert($string);
            $index ++;
        }
    }

    /**
     * Get the file name string suffix
     *
     * @param  string $fileName     the file name
     * @return string $pix          example(".jpg")
     */
    public static function getSuffix($fileName)
    {
        $pix = strtolower ( strrchr ( $fileName, '.' ) );
        return $pix;
    }

    /**
     * Returns an array value for a path
     *
     * @param array  $values   The values to search
     * @param string $name     The token name
     * @param array  $default  Default if not found
     *
     * @return array
     */
    public static function getArrayValueForPath($values, $name, $default = null)
    {
        if (false === $offset = strpos ( $name, '[' ))
        {
            return isset ( $values [$name] ) ? $values [$name] : $default;
        }

        if (! isset ( $values [substr ( $name, 0, $offset )] ))
        {
            return $default;
        }

        $array = $values [substr ( $name, 0, $offset )];

        while ( false !== $pos = strpos ( $name, '[', $offset ) )
        {
            $end = strpos ( $name, ']', $pos );
            if ($end == $pos + 1)
            {
                // reached a []
                if (! is_array ( $array ))
                {
                    return $default;
                }
                break;
            }
            else if (! isset ( $array [substr ( $name, $pos + 1, $end - $pos - 1 )] ))
            {
                return $default;
            }
            else if (is_array ( $array ))
            {
                $array = $array [substr ( $name, $pos + 1, $end - $pos - 1 )];
                $offset = $end;
            }
            else
            {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Strip slashes recursively from array
     *
     * @param  array $value  the value to strip
     * @return array clean value with slashes stripped
     */
    public static function stripslashesDeep($value)
    {
        return is_array ( $value ) ? array_map ( array ('TMUtil', 'stripslashesDeep' ), $value ) : stripslashes ( $value );
    }

    /**
     * Filter text recursively from array
     *
     * @access public
     * @param  mixed $value     the value to filter text
     * @return mixed  $result   clean value with filtered
     */
    public static function filterTextDeep($value)
    {
        return is_array($value)?array_map(array("TMUtil", "filterTextDeep"), $value) : TMFilterUtils::filterText($value,false);
    }

    /**
     * 处理query字符串，例如a=b&c=d
     *
     * @param  string $string     处理源字符串
     * @return array $resultArray       结果数组
     */
    public static function handleQueryString($string)
    {
        $tmpArray = explode("&", $string);
        $resutlArray = array();
        foreach($tmpArray as $tmp)
        {
            $tmpArray2 = explode("=",$tmp);
            if(isset($tmpArray2[1]))
            {
               $resutlArray[$tmpArray2[0]] = $tmpArray2[1];
            }else{
               $resutlArray[$tmpArray2[0]] = "";
            }
        }

        return $resutlArray;
    }

    /**
     * Get client ip address
     *
     * @return string $ip    the client ip address
     */
    public static function getClientIp()
    {
        if (isset ( $_SERVER ['HTTP_QVIA'] ))
        {
            $ip = qvia2ip ( $_SERVER ['HTTP_QVIA'] );
            if ($ip)
            {
                return $ip;
            }
        }

        if (isset ( $_SERVER ['HTTP_CLIENT_IP'] ) and ! empty ( $_SERVER ['HTTP_CLIENT_IP'] ))
        {
            return TMFilterUtils::filterIp ( $_SERVER ['HTTP_CLIENT_IP'] );
        }
        if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) and ! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ))
        {
            $ip = strtok ( $_SERVER ['HTTP_X_FORWARDED_FOR'], ',' );
            do
            {
                $ip = ip2long ( $ip );

                //-------------------
                // skip private ip ranges
                //-------------------
                // 10.0.0.0 - 10.255.255.255
                // 172.16.0.0 - 172.31.255.255
                // 192.168.0.0 - 192.168.255.255
                // 127.0.0.1, 255.255.255.255, 0.0.0.0
                //-------------------
                if (! (($ip == 0) or ($ip == 0xFFFFFFFF) or ($ip == 0x7F000001) or (($ip >= 0x0A000000) and ($ip <= 0x0AFFFFFF)) or
                    (($ip >= 0xC0A8FFFF) and ($ip <= 0xC0A80000)) or (($ip >= 0xAC1FFFFF) and ($ip <= 0xAC100000))))
                {
                    return long2ip ( $ip );
                }
            }
            while ( $ip = strtok ( ',' ) );
        }
        if (isset ( $_SERVER ['HTTP_PROXY_USER'] ) and ! empty ( $_SERVER ['HTTP_PROXY_USER'] ))
        {
            return TMFilterUtils::filterIp ( $_SERVER ['HTTP_PROXY_USER'] );
        }
        if (isset ( $_SERVER ['REMOTE_ADDR'] ) and ! empty ( $_SERVER ['REMOTE_ADDR'] ))
        {
            return TMFilterUtils::filterIp ( $_SERVER ['REMOTE_ADDR'] );
        }
        else
        {
            return "0.0.0.0";
        }
    }

    /**
     * Get all page for the table
     *
     * @param  int $allCount     the count of all records
     * @param  int $countOnePage the count of one page
     * @return int $allPage      the count of all page
     */
    public static function getAllPage($allCount, $countOnePage)
    {
        if ($allCount == 0)
        {
            $allPage = 0;
        }
        else if($countOnePage == 0)
        {
            $allPage = 0;
        }
        else
        {
            if ($allCount % $countOnePage != 0)
            {
                $allPage = intval ( $allCount / $countOnePage ) + 1;
            }
            else
            {
                $allPage = $allCount / $countOnePage;
            }
        }

        if($allPage == 0)
        {
            $allPage = 1;
        }
        return $allPage;
    }

    /**
     * Get the length of string
     *
     * @param  string $str        the string
     * @return string $length     the string' text length
     */
    public static function getStringLength($str)
    {
        $start = 0;
        $len = strlen ( $str );
        $r = array ();
        $n = 0;
        $m = 0;
        for($i = 0; $i < $len; $i ++)
        {
            $x = substr ( $str, $i, 1 );
            $a = base_convert ( ord ( $x ), 10, 2 );
            $a = substr ( '00000000' . $a, - 8 );
            if ($n < $start)
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $i += 2;
                }
                $n ++;
            }
            else
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                    $r [] = substr ( $str, $i, 1 );
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $r [] = substr ( $str, $i, 2 );
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $r [] = substr ( $str, $i, 3 );
                    $i += 2;
                }
                else
                {
                    $r [] = '';
                }
            }
        }

        return count($r);
    }

    /**
     * Get short text
     *
     * @param  string $str     the origin string
     * @param  int    $lenth   the number n1%3 = 0 because utf
     * @param  string $etc     the short tail
     * @return string $str     the changed string
     */
    public static function getShortText($str, $lenth = 80, $etc = '...')
    {
        $start = 0;
        $len = strlen ( $str );
        $r = array ();
        $n = 0;
        $m = 0;
        for($i = 0; $i < $len; $i ++)
        {
            $x = substr ( $str, $i, 1 );
            $a = base_convert ( ord ( $x ), 10, 2 );
            $a = substr ( '00000000' . $a, - 8 );
            if ($n < $start)
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $i += 2;
                }
                $n ++;
            }
            else
            {
                if (substr ( $a, 0, 1 ) == 0)
                {
                    $r [] = substr ( $str, $i, 1 );
                }
                elseif (substr ( $a, 0, 3 ) == 110)
                {
                    $r [] = substr ( $str, $i, 2 );
                    $i += 1;
                }
                elseif (substr ( $a, 0, 4 ) == 1110)
                {
                    $r [] = substr ( $str, $i, 3 );
                    $i += 2;
                }
                else
                {
                    $r [] = '';
                }
                if (++ $m >= $lenth)
                {
                    break;
                }
            }
        }
        $trunstr = join ( '', $r );
        if (strlen ( $trunstr ) < $len)
            return $trunstr . $etc;
        else
            return $trunstr;
    }

    /**
     * buildWhereString
     * 生成sql中的where 子句
     *
     * @param  array $input     where子句的键值对数组
     * @return string    sql中的where子句，如果参数不为数组则返回false
     */
    public static function buildWhereString($input)
    {
    	if(is_array($input))
    	{
            $arrayStringColumn = TMConfig::mysqlStringColumns();
            $updateString = "1 ";
    		foreach ($input as $key => $field)
    		{
    			if (!empty($arrayStringColumn))
                {
                    if (in_array($key, $arrayStringColumn))
                    {
                        $field = strval($field);
                    }
                    else if (is_numeric($field))
                    {
                        $field = intval($field);
                    }

                    if (is_string($field))
                    {
                        $updateString .= "and ".$key . " = '" . TMFilterUtils::filterSqlParameter($field) . "' ";
                    }
                    else
                    {
                        $updateString .= "and ".$key . " = " . $field . " ";
                    }
                }
    		}

    		return $updateString;
    	}else{
    		return "";
    	}
    }

    /**
     * 处理curl发送参数的格式化
     *
     * @param array $array 要处理的参数键值对数组
     * @param string $connetChar 分割符号
     * @return string $result
     */
    public static function handleParameter($array, $connetChar='&')
    {
        $result = "";
        $i = 0;
        foreach($array as $key => $value)
        {
            $value = str_replace($connetChar, "", $value);
            if ($i == 0)
            {
                $result = $result.$key."=".$value;
            }
            else
            {
                $result = $result.$connetChar.$key."=".$value;
            }
            $i++;
        }
        return $result;
    }

	/**
	 * getExchangeTimes
	 * 用户提交兑换码错误连续达到一定数量，封停用户在规则时间内不能再进行兑换
	 *
	 * @param  string $userkey     缓存中每个QQ对应的键值
	 * @param  int    $times   最多可连续失败次数
	 * @param  int 	  $expire  封停的时间
	 * @param  bool   $flag    兑换码是否有效
	 *
	 * @return array
	 */
	public static function getExchangeTimes($userkey,$times,$expire,$flag){
        $memCache = TMMemCacheMgr::getInstance();
        $exchangeTimes = array();
        $key = TMConfig::TAMS_ID."_".$userkey."_ExchangeTimes on ".date("Y-m-d");
        //$memCache->clear($key,false);
        $value = $memCache->get($key);
        $exchangeTimes["status"] = false;
        //如果超过5次，封停用一天,返回失败
        if($value && $value>($times-1))
        {
        	$exchangeTimes["times"] = $value;
        	$exchangeTimes["remain"] = 0;
        	return 	$exchangeTimes;
        }
        if($flag)
        {
        	//有效兑换码则清除cache值
        	if($value)
        	{
        		TMDebugUtils::debugLog('memcache clear: '.$key);
        		$memCache->clear($key,false);
        	}
			$exchangeTimes["status"] = true;
			$exchangeTimes["times"] = 0;
			$exchangeTimes["remain"] = $times;
        } else {
        	//无效兑换码则累加cache值
	        if($value){
        		$value++;
//        		TMDebugUtils::debugLog('memcache set '.$key.' to '.$value);
        		$memCache->set($key,$value,$expire);
        		$exchangeTimes["times"] = $value;
        		$exchangeTimes["remain"] = $times - $value;
	        } else {
	        	//TMDebugUtils::debugLog('memcache set '.$key.' to 1');
	        	$memCache->set($key,1,$expire);
	        	$exchangeTimes["times"] = 1;
	        	$exchangeTimes["remain"] = $times - 1;
	        }
        }
        return 	$exchangeTimes;
	}
}
