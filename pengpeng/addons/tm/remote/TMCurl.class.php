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
 * Encapsulate the curl function to open remote url infterface
 *
 * @package lib.remote
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-12-26
 */
class TMCurl
{
    /**
     * @var resource    curl instance
     *
     * @access private
     */
    private $channel;

    /**
     * @var string    the curl url address
     *
     * @access private
     */
    private $url;

    /**
     * @var array    set curl options array
     *
     * @access private
     */
    private $optionArray;

    /**
     * construct
     *
     * @access public
     * @param  string $url     the curl url address
     * @return void
     */
    public function __construct($url = null)
    {
            $this->url = $url;
            $this->channel = curl_init($url);
            $this->optionArray = array();
    }

    /**
     * destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        curl_close($this->channel);
    }

    /**
     * Set the options of CURL visit. About the option constant option, you could search "curl" from PHP manual
     *
     * @param array $arrayOption  The array of options, it is as array(CURL_HEADER=>false). CURL_HEADER is the CURL library constant,
     * you could find all constants definition in the PHP manual.
     * Example:
     * $option_array = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_COOKIE => 'uin='.$_COOKIE["uin"].';skey='.$_COOKIE["skey"].';zzpaneluin='.$_COOKIE["zzpaneluin"].';zzpanelkey='.$_COOKIE["zzpanelkey"],
            CURLOPT_POSTFIELDS => "hang_annex=1&albumlife[0][o]=http://fordfiesta.qzone.qq.com/data/mini_23973630_2009_03_01_15_22_14_1.jpg
            &albumlife[0][t]=http://fordfiesta.qzone.qq.com/data/mini_23973630_2009_03_01_15_22_14_1.jpg",
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_HTTPHEADER => array(
                'Accept-Language:zh-cn',
                'Connection:Keep-Alive',
                'Content-Type: application/x-www-form-urlencoded'
            )
        );
     */
    public function setOptions($arrayOption)
    {
        $this->optionArray = $arrayOption;
    }


    /**
     * this is the necessary step. After this method is executed, all options set will be effective
     *
     * @access public
     * @return string $result
     * @throws TMRemoteException
     */
    public function execute()
    {
        curl_setopt_array($this->channel,$this->optionArray);
        $result = curl_exec($this->channel);
        if(curl_errno($this->channel) != 0)
        {
            new nbLog("Remote visiting is failed:".$this->url);
            throw new TMRemoteException("Remote visiting is failed:".$this->url);
        }
        else
        {
          new nbLog("Remote visiting result is :".$result);
            return $result;
        }
    }

    /**
     * send a post curl request
     *
     * @access public
     * @param  array $arrayParam     发送参数的键值对数组
     * @param string $url
     * @return string $result
     */
    public function sendByPost($arrayParam,$url="")
    {
        return $this->send($arrayParam,true,$url);
    }

    /**
     * send a get curl request
     *
     * @access public
     * @param  array $arrayParam 发送参数的键值对数组
     * @param  string $url
     * @return string $result
     */
    public function sendByGet($arrayParam, $url="")
    {
        return $this->send($arrayParam,false,$url);
    }

    /**
     * get the information of the channel. About the detail of parameter $opt, please read the php manual.
     *
     * @access public
     * @param  array $opt     查询可选参数
     * @return mixed $result
     */
    public function getInfo($opt=null)
    {
        return curl_getinfo($this->channel,$opt);
    }

    /**
     * 将curl实例返回
     *
     * @access public
     * @return resource $channel
     */
    public function get()
    {
        return $this->channel;
    }

    /**
     * Set virtual host when using curl
     *
     * @param  string $hostString     the host string
     * @return void
     */
    public function setVHost($hostString)
    {
        $option_array = $this->optionArray;
        if (isset($option_array[CURLOPT_HTTPHEADER]))
        {
          $headerArray = $option_array[CURLOPT_HTTPHEADER];
        }

        $headerArray[] = "Host: ".$hostString;

        $option_array[CURLOPT_HTTPHEADER] = $headerArray;

        $this->setOptions($option_array);
    }

    /**
     * send post or get curl request
     *
     * @access private
     * @param  array $arrayParam    发送参数的键值对数组
     * @param bool $post            是否是post方法，post方法为true
     * @param string $url           curl request url
     * @return string $result
     */
    private function send($arrayParam,$post=true,$url="")
    {
        if (empty($url))
        {
            $url = $this->url;
        }
        $cookies = TMUtil::handleParameter($_COOKIE, "; ");
        $option_array = $this->optionArray;
        $option_array[CURLOPT_HEADER] = 0;
        $option_array[CURLOPT_COOKIE] = $cookies;
        $option_array[CURLOPT_RETURNTRANSFER] = 1;

        if($post)
        {
            $option_array[CURLOPT_URL] = $url;
            $option_array[CURLOPT_POST] = 1;
            $option_array[CURLOPT_POSTFIELDS] = $arrayParam;
        }
        else
        {
            $parameter = TMUtil::handleParameter($arrayParam);
            $option_array[CURLOPT_URL] = $url. "?" . $parameter;
        }

        $this->setOptions($option_array);
        return $this->execute();
    }
}