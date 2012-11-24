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
 * 图片验证
 * @author Jackxiang@tencent.com
 * @version 2010-08-30 gastonwu@tencent.com
 */
class TMValidPic {

    /**
     * 检查图片验证码输入是否正确
     * @param string $vkey 前端提交的验证码
     * @param int $expire 过期时间
     * @return boolean 成功返回true,失败返回false
     */
    public static function verifyVkey($vkey, $expire = 1800) {
        $appid = nbHelper::getConfig('minisite/appid');
        return self::verifyKeyFromSo($vkey, $appid, $expire);
    }

    private static function verifyKeyFromSo($vkey, $appid = '', $expire = PL2_VKEY_VALIDTIME)
    {
      $appid = $appid ? $appid : nbHelper::getConfig('minisite/appid');
      $sid = isset($_COOKIE[PL2_VC_NAME_SESSION])? $_COOKIE[PL2_VC_NAME_SESSION]: '';
      //绿色环保,用了就删
      setcookie( 'verifysession', false, $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );

      $i = strlen($sid);

      if ( strlen($vkey) < 4 || $i < 10 )
      {
          return false;
      } elseif ( $i <= 70 ) {
          return false; //@qp_imgkey_verify($sid, $vkey);
      } else {
          //取得ptlogin2业务ID
          return qp_pt2vkey_verify4($vkey, $sid, $expire, $appid, PL2_VKEY_SERVER1_HOST
          , PL2_VKEY_SERVER1_PORT, PL2_VKEY_SERVER2_HOST, PL2_VKEY_SERVER2_PORT, TMUtil::getClientIp());
      }
    }

//    /**
//     * 大图片验证码验证(同时兼容第一代和第二代)
//     *
//     * @param   string  vkey     vkey code input by user
//     * @param   int[optional=0]  appid      Application ID.
//     * @param   int[optional]    expire     expire time.
//     */
//    private static function qp_vkey_verify($vkey, $appid = 0, $expire = PL2_VKEY_VALIDTIME) {
//        $sid = isset($_COOKIE['verifysession']) ? $_COOKIE['verifysession'] : '';
//        //绿色环保,用了就删
//        setcookie('verifysession', false, $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com');
//
//        $i = strlen($sid);
//
//        if (strlen($vkey) < 4 || $i < 10) {
//            return false;
//        } elseif ($i <= 70) {
//            return false; //@qp_imgkey_verify($sid, $vkey);
//        } else {
//            //取得ptlogin2业务ID
//            if ($appid == 0) {
//                $appid = qp_get_pt2appid();
//            }
//
//            return @qp_pt2vkey_verify($vkey, $sid, PL2_VKEY_VALIDTIME, $appid, PL2_VKEY_SERVER1_HOST, PL2_VKEY_SERVER1_PORT, PL2_VKEY_SERVER2_HOST, PL2_VKEY_SERVER2_PORT);
//        }
//    }

}