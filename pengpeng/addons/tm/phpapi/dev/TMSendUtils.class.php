<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
  * The util class for sending virtual goods
  *
  * @package lib.util
  * @author  ianzhang <ianzhang@tencent.com>
  * @version TMSendUtils.class.php 2009-9-27 by ianzhang
  */
 class TMSendUtils
 {
     /**
      * send qzone blog
      *
      * @param  int $qq     qq number,注意是整型
      * @param  string $title     blog title string
      * @param  string $content    blog content string
      * @return boolean $result       true: success, false:failure
      */
    public static function sendBlog($qq, $title, $content)
    {
         $qq = intVal($qq);

         $socket = new TMSocket();

         $socket->connect(TMConfig::getConfig("blog","blogip"),9000);

        //head
        $stx = iconv("utf-8","gb2312",0x04);
        $sendBuffLen = iconv("utf-8","gb2312",400);
        $version = iconv("utf-8","gb2312",1);
        $seqNo = iconv("utf-8","gb2312",0);
        $cmd = iconv("utf-8","gb2312",110);
        $appid = iconv("utf-8","gb2312",10);
        $reserve = iconv("utf-8","gb2312",0);

        $qq = iconv("utf-8","gb2312",$qq);
        $title = iconv("utf-8","gb2312",$title)."\0";
        $lengthTitle = strlen($title);
        $content = iconv("utf-8","gb2312",$content)."\0";
        $lengthContent = strlen($content);

        $etx = iconv('utf-8',"gb2312",0x05);

        $data = pack("cllllllNna".$lengthTitle."na".$lengthContent."c",$stx,$sendBuffLen,$version,$seqNo,$cmd,$appid,$reserve
            ,$qq,$lengthTitle,$title,$lengthContent,$content,$etx);

        $result = $socket->sendData($data);

        $socket->close();
        if($result > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
     }

     /**
      * 发送QQ秀
      *
      * @param  string $proname    项目名字
      * @param  string $proid      发送的项目id。类似MP********
      * @param  string $proitem    营销平台的物品号，类似goodsset*********
      * @param  string $qq         qq号码
      *
      * @return boolean
      */
     public static function sendQQShow($proname, $proid, $proitem, $qq)
     {
        new nbLog("$proname, $proid, $proitem, $qq");
        $url = "http://emarketing.qq.com/cgi-bin/em_sendqqshow";
        if (isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == "test")
        {
            $url .= "_test";
        }
        $service = new TMCurl($url);
        $key = md5($proname . "*" . $qq);
        $result = $service->sendByGet(array('proname'=>$proname,'proid'=>$proid,'proitem'=>$proitem,'qq'=>$qq, 'propwd'=>$key));
        if ($result == "result=0")
        {
            return true;
        }
        return false;
    }

    /**
     * 发送Qzone挂件
     *
     * @param  int $actid     the qzone send action id
     * @param  string $qq    the qq number
     * @param  array $option  the option array, for example: array("hang_annex" => 0)
     * @param  array $ipList   array('10.1.1.154','1.11.134.134')
     * @param  string $url     进行发送挂件接口的地址
     * @return return   boolean  $result
     */
    public static function sendGuajian($actid, $qq, $option = array(), $ipList = array(), $url = "/user_v3/freereg.php")
    {
        if (count($ipList) == 0)
        {
            $ipList = TMConfig::getConfig('guajian_ip');
        }
        $listCount = count($ipList);

        $number = mt_rand(0,$listCount-1);

        TMDebugUtils::debugLog("http://".$ipList[$number].$url."?act_id=".$actid."&unicode");

        $curl =  new TMCurl("http://".$ipList[$number].$url."?act_id=".$actid."&unicode");

        if(count($option) == 0)
        {
            $fields = array(
                    "hang_annex" => 1
            );
        }
        else
        {
            $fields = $option;
            if(!array_key_exists("hang_annex",$option))
            {
                $fields["hang_annex"] = 1;
            }
        }

        $fields["qq"] = $qq;

        $fields_string = TMUtil::handleParameter($fields);
		$cookie_string = TMUtil::handleParameter($_COOKIE, ";");

        $option = array(
            CURLOPT_HEADER =>0,
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_COOKIE => $cookie_string,
            CURLOPT_POST => count($fields),
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => array('Host: act.qzone.qq.com')
            );

        $curl->setOptions($option);
        try
        {
            $result = $curl->execute();
        }
        catch(TMException $te)
        {
            TMDebugUtils::debugLog($te->getMessage());
            return false;
        }

        TMDebugUtils::debugLog($result);

        $result_array = json_decode($result,true);
        if(array_key_exists('err', $result_array))
        {
          $path = CoreConfigTool::getConfig('minisite/logPath');
            $log = new TMLog($path);
            $log->la($qq." error in: ".$result_array["msg"]);

            return false;
        }

        return true;
    }

    /**
     * 发送带图片的Qzone Feeds
     * 特别注意如果Qzone开放给所有用户的话，图片如果是放在广平服务器可能会压力较大支持不住
     * 支持弱验证的Qzone服务器是他们的正式服务器，需要他们发布以后才能测试。
     * 同一个actid无论是测试环境还是正式环境，只要发过一次就不能再发送了。
     *
     * @param int $actid the qzone send action id
     * @param string $qq the qq number
     * @param array $images 图片数组，包含若干组图片，每组图片则有两张一张为原始大小的图片，另外一张为缩略图；例如 array("0"=>array("o"=>"http://xxx/xxx.jpg","t"=>"http://xxx/xxx1.jpg"));
     * @return boolean true：发送成功，false：发送失败
     */
    public static function sendImageFeeds($actid, $qq, $images=array())
    {
        //支持弱验证的Qzone服务器IP
        $ip = '172.23.129.119';
        $curl =  new TMCurl("http://".$ip."/user/freereg.php?act_id=".$actid."&unicode");

        $fields = array();
        foreach ($images as $k=>$image)
        {
            $fields["albumart[$k][o]"] = $image['o'];
            $fields["albumart[$k][t]"] = $image['t'];
        }

        //以弱验证的方式传入用户登录信息
		$fields['uin'] = $qq;
		$fields['md5'] = md5('mudi5n'.$qq.'#$ui982~)=c+^%3*');
		$fields["qq"] = $qq;

		//过滤、拼接传入参数
		$fields_string = TMUtil::handleParameter($fields);
		//过滤、拼接cookie
		$cookie_string = TMUtil::handleParameter($_COOKIE, ";");

		//设置curl options
		$option = array(
    		CURLOPT_HEADER => 0,
    		CURLOPT_RETURNTRANSFER => 1,
    		CURLOPT_COOKIE => $cookie_string,
    		CURLOPT_POST => count($fields),
    		CURLOPT_POSTFIELDS => $fields_string,
    		CURLOPT_HTTPHEADER => array('Host: act.qzone.qq.com')
    		);
		$curl->setOptions($option);

		try
		{
			$result = $curl->execute();
		}
		catch (TMException $te)
		{
			TMDebugUtils::debugLog($te->getMessage());
			return false;
		}

		$result_array = json_decode($result, true);
		if (array_key_exists('err', $result_array))
		{
			$log = new TMLog(TMConfig::ERRORLOG . "_qzone_guajian_failure");
			$log->la($qq . $result_array ['err']);

			TMDebugUtils::debugLog("Send Guajian Array, err: ".$result_array['err'].", msg: ".$result_array['msg']);
			return false;
		}

		return true;
    }
 }