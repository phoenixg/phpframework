<?php
//二进制打包类
class BinaryPacket
{
    public $body;

    function __construct($body = ''){
        $this->body = $body;
    }

    public function addUChar($i){
        $this->body .= pack('C', $i);
    }

    public function addUShort($i){
        $this->body .= pack('n', $i);
    }

    public function addUInt($i){
        $this->body .= pack('N', $i);
    }

    public function addInt($i){
        $this->body .= pack('N', $i);
    }

    public function addShortString($s){
        if ( $s == '' ){
            $this->body .= pack('C', 0);
        } else {
            $this->body .= pack('C', strlen($s)).$s;
        }
    }

    public function addString($s){
        if ( $s == '' ){
            $this->body .= pack('n', 0);
        } else {
            $this->body .= pack('n', strlen($s)).$s;
        }
    }

    public function getUChar(){
        $ret = @unpack('Cret', $this->body);
        if ( $ret == false ){
            return null;
        }
        $this->body = substr($this->body, 1);
        return $ret['ret'];
    }

    public function getUShort(){
        $ret = @unpack('nret', $this->body);
        if ( $ret == false ){
            return null;
        }
        $this->body = substr($this->body, 2);
        return $ret['ret'];
    }

    public function getUInt(){
        $ret = @unpack('nhi/nlo', $this->body);
        if ( $ret == false ){
            return null;
        }
        $this->body = substr($this->body, 4);
        return (($ret['hi'] << 16) | $ret['lo']);
    }

    public function getInt(){
        $ret = @unpack('Nret', $this->body);
        if ( $ret == false ){
            return null;
        }
        $this->body = substr($this->body, 4);
        return $ret['ret'];
    }

    public function getString(){
        $ret = @unpack('Clen', $this->body);
        if ( $ret == false ){
            return null;
        }
        $rets = substr($this->body, 1, $ret['len']);
        $this->body = substr($this->body, $ret['len'] + 1);
        return $rets;
    }

    public function getStdString(){
        $p = strpos($this->body, "\0");
		if ( $p === FALSE ){
			return null;
		}

        if ( $p == 0 ){
            $rets = '';
        } else {
	        $rets = substr($this->body, 0, $p);
		}
        $this->body = substr($this->body, $p + 1);
        return $rets;
    }

    public function getReset(){
        $ret = $this->body;
        $this->body = '';
        return $ret;
    }
}

/* {{{ function qp_http_get() */
/**
 * 向一个URL发请求
 *
 * @param	int		$uin	用户QQ号码
 * @return  true: ok; false: fail.
 */
function qp_http_get($url, $params, &$errsz, $type = 'GET', $conntimeout = 2, $exectimeout = 4)
{
	$curlsess = curl_init();
	// connection timeout
	curl_setopt($curlsess, CURLOPT_CONNECTTIMEOUT, $conntimeout);
	// excute timeout
	curl_setopt($curlsess, CURLOPT_TIMEOUT, $exectimeout);

	$host = '';
	if ( is_array($params) )
	{
		//记下域名
		if ( !empty($params['HOST']) )
		{
			$host = $params['HOST'];
			unset($params['HOST']);
		}

		$qs = array();
		foreach ($params as $key => $val)
		{
			if ( is_array($val) )
			{
				foreach ( $val as $sval )
				{
					$qs[] = urlencode($key).'='.urlencode($sval);
				}
			} else {
				$qs[] = urlencode($key).'='.urlencode($val);
			}
		}
		$params = implode('&', $qs);
	}

	if (strcmp($type, 'GET') == 0)
	{
		curl_setopt($curlsess, CURLOPT_URL, $url.'?'.$params);
	} else {
		curl_setopt($curlsess, CURLOPT_URL, $url);
		curl_setopt($curlsess, CURLOPT_POST, TRUE);
		curl_setopt($curlsess, CURLOPT_POSTFIELDS, $params);
	}

	//传递cookie的值，用于传递登录态
	if ( !empty($_SERVER['HTTP_COOKIE']) )
	{
		curl_setopt($curlsess, CURLOPT_COOKIE, $_SERVER['HTTP_COOKIE']);
	}

	//判断是否需要域名支持
	if ( !empty($host) )
	{
		curl_setopt($curlsess, CURLOPT_HTTPHEADER, array('Host: '.$host));
	}

	curl_setopt($curlsess, CURLOPT_RETURNTRANSFER, TRUE);

	$response = curl_exec($curlsess);
	$ret = curl_getinfo($curlsess);
	curl_close($curlsess);
	$errsz = $ret['http_code'];

	if ( $ret['http_code'] >= 200 && $ret['http_code'] < 400 && $response != '' )
	{
		return $response;
	} else {
		return false;
	}
}

/* }}} */


/* {{{ function var_encoding() */
/**
 * 转码
 *
 * @param	array	&$data	要被转的数组
 * @return  array: ok; false: fail.
 */
function output_encoding($obj){
	if ( !defined('CHARSET') || strcasecmp(CHARSET, 'GB2312') == 0 ){
		return $obj;
	}

	if ( is_string($obj) ){
		$obj = iconv('gb18030', 'utf-8//IGNORE', $obj);
	} else if ( is_array($obj) ) {
		foreach ( $obj as $k => &$v ){
			$v = output_encoding($v);
		}
	}

	return $obj;
}
/* }}} */


/* {{{ function var_encoding() */
/**
 * 转码
 *
 * @param	array	&$data	要被转的数组
 * @return  array: ok; false: fail.
 */
function input_encoding($obj){
	if ( !defined('CHARSET') || strcasecmp(CHARSET, 'GB2312') == 0 ){
		return $obj;
	}

	if ( is_string($obj) ){
		$obj = iconv('utf-8', 'gb18030//IGNORE', $obj);
	} else if ( is_array($obj) ) {
		foreach ( $obj as $k => &$v ){
			$v = input_encoding($v);
		}
	}

	return $obj;
}
/* }}} */

//End of script
