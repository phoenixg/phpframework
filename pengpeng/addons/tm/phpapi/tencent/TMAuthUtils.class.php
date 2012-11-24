<?php

/*
 * ---------------------------------------------------------------------------
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
 * ---------------------------------------------------------------------------
 */



/**
 * Class: TMAuthUtils
 * Description: The Auth Filter
 *
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-11
 * @version 2010-08-30 gastonwu@tencent.com
 */
class TMAuthUtils {

    /**
     * 检验是否登陆状态，需要设定全局变理$_SERVER['APPID']
     * @return boolean 已登陆返回true,未登陆返回false
     */
    public static function isLogin() {
        $iRet = self::getUinFromSo(nbHelper::getConfig('minisite/appid'), true);
        //$iRet = intval($iRet);
        if ($iRet < 10000) {
            setCookie("uin", '', 0, "/", "qq.com");
            setCookie("skey", '', 0, "/", "qq.com");
            return false;
        } else {
            return true;
        }
    }

    /**
     * 取得当前的QQ号
     * @param string $appid APPID全局设定
     * @param <type> $verify
     * @return int 未登陆时返回0,已登陆时返回QQ号
     */
    public static function getUin($appid = '', $verify = true) {
        $appid = $appid ? $appid : nbHelper::getConfig('minisite/appid');
        $qq = self::getUinFromSo($appid, $verify);
        if ($qq < 10000) {
            setCookie("uin", '', 0, "/", "qq.com");
            setCookie("skey", '', 0, "/", "qq.com");
            //throw new TMException("请您先登录QQ号码");
        } else {
            return $qq;
        }
    }

    /**
     * Get user info from ptlogin2
     *
     * @param int $appid appid for logining
     *
     * @return array
     */
    public static function getNick($appid = '')
    {
      $appid = $appid ? $appid : nbHelper::getConfig('minisite/appid');

      ob_start();
      $userinfo = self::getUserInfoFromSo($appid);
      ob_end_clean();

      if (is_array($userinfo) && isset($userinfo['nick']))
      {
        $nickname = $userinfo['nick'];
      }

      return $nickname;
    }

  public static function getUDSInfo($qq)
  {
    $curl = new TMCurl();
    $json = $curl->sendByGet(array("qq" => $qq), 'http://emarketing.qq.com/cgi-bin/nick/getUDSInfo');
    $json = iconv("GB2312", "UTF-8", $json);
    $result = json_decode($json, true);

    return $result;
  }

  public static function getNickByQQ($qq)
  {
    TaeCore::taeInit(TaeConstants::SERVER_IP, '10.128.69.47');
    TaeCore::taeInit(TaeConstants::SERVER_PORT, 26000);

    $imResult = TaeIMService::getNick($qq);
    if($imResult["retcode"] == '0')
    {
      $nicknamelist = $imResult["nicknamelist"];
      $result["nick"] = $nicknamelist[$qq];
    }
    else
    {
      $result["nick"] = $qq;
    }

    return $result["nick"];
  }

  private static function getUinFromSo($appid = '', $verify = true )
  {
    $appid = $appid ? $appid : nbHelper::getConfig('minisite/appid');

    $ckuin  = empty($_COOKIE[PL2_SESS_NAME_UIN])? '0': $_COOKIE[PL2_SESS_NAME_UIN];
    $p = ord($ckuin[0]);
    $uin = ($p < 0x30 || $p > 0x39)? floatval(substr($ckuin, 1)): floatval($ckuin);
    $ret = 0;

    if ( !empty($uin) && $uin >= 10000 )
    {
        if ( $verify == true )
        {
            $skey = isset($_COOKIE[PL2_SESS_NAME_KEY])? $_COOKIE[PL2_SESS_NAME_KEY]: '';

            if ( !empty($skey) )
            {
                if ( strlen($skey) > 15 || substr($skey,0,1) == '@' )
                {
                    $ret = qp_pt2sess_verify4($uin, $skey, $appid, false, PL2_SESS_SERVER1_HOST
                    , PL2_SESS_SERVER1_PORT, PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT, TMUtil::getClientIp());
                }
            }
        } else {
            $ret = $uin;
        }
    }
    return $ret;
  }

  private static function getUserInfoFromSo($appid = '')
  {
    $appid = $appid ? $appid : nbHelper::getConfig('minisite/appid');

    $ckuin  = empty($_COOKIE[PL2_SESS_NAME_UIN])? '0': $_COOKIE[PL2_SESS_NAME_UIN];
    $p = ord($ckuin[0]);
    $uin = ($p < 0x30 || $p > 0x39)? floatval(substr($ckuin, 1)): floatval($ckuin);
    $ret = false;

    if ( !empty($uin) && $uin >= 10000 )
    {
        $skey = isset($_COOKIE[PL2_SESS_NAME_KEY])? $_COOKIE[PL2_SESS_NAME_KEY]: '';
        if ( !empty($skey) )
        {
            if ( strlen($skey) > 15 || substr($skey,0,1) == '@' )
            {
                $ret = qp_pt2sess_verify4($uin, $skey, $appid, true, PL2_SESS_SERVER1_HOST
                , PL2_SESS_SERVER1_PORT, PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT, TMUtil::getClientIp());
            }
        }
    }
    return( $ret );
  }
}

?>