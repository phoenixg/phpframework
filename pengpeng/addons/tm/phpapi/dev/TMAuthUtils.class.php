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
 * Class: TMAuthUtils
 * Description: The Auth Mock
 *
 * @author  gastonwu@tencent.com
 * @version 2010-08-30
 */
class TMAuthUtils{

	/**
	 * 检验是否登陆状态
	 * @return boolean 已登陆返回true,未登陆返回false
	 */
	public static function isLogin(){
          return isset($_COOKIE['uin']);
	}


        /**
         * 取得当前的QQ号
         * @param string $appid APPID全局设定
         * @param <type> $verify
         * @return int 未登陆时返回0,已登陆时返回QQ号
         */
	public static function getUin($appid = '', $verify = true) {
    return isset($_COOKIE['uin']) ? floatval(substr($_COOKIE['uin'], 1)) : 0;
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
    return 'Vendor User';
  }

  public static function getNickByQQ($qq)
  {
    return 'Vendor User';
  }

  public static function getUDSInfo($qq)
  {
    return array('qq'=>$qq, 'age'=>27, 'province'=>12, 'gender'=>1, 'city'=>0);
  }
}
?>