<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | Tencent Qzone Protal PHP Library Include File.                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007 Tencent Inc. All Rights Reserved.                 |
// +----------------------------------------------------------------------+
// | Authors: The Qzone Portal Dev Team, ISRD, Tencent Inc.               |
// |          Kulin <kulin@tencent.com>                                   |
// +----------------------------------------------------------------------+

/**
 * @version   0.1.2
 * @author    Kulin <kulin@tencent.com>
 * @date      2007.8.15 10:16
 * @brief.    QPLIB_MAIN
 */
define( 'QP_ZZPANEL_UIN', 'zzpaneluin' );
define( 'QP_ZZPANEL_KEY', 'zzpanelkey' );
define( 'QP_ZZPANEL_EXP', (4*3600) );

define( 'QP_SESS_NAME_UIN' , 'uin');
define( 'QP_SESS_NAME_KEY' , 'skey');

define( 'PL_SESS_SERVER1_HOST', '172.16.208.23' );
define( 'PL_SESS_SERVER1_PORT', 8889 );

//get_cfg_var('qplib.devmode');
if ($_SERVER['SERVER_TYPE'] == 'test' )
{
    //开发机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER1_PORT', 58001 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER2_PORT', 58001 );

    define( 'PL2_SESS_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER1_PORT', 58000 );
    define( 'PL2_SESS_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER2_PORT', 58000 );
} else {
    //运营机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.23.32.42' );
    define( 'PL2_VKEY_SERVER1_PORT', 18888 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.23.32.44' );
    define( 'PL2_VKEY_SERVER2_PORT', 18888 );

    define( 'PL2_SESS_SERVER1_HOST', '172.16.85.48' );
    define( 'PL2_SESS_SERVER1_PORT', 18891 );
    define( 'PL2_SESS_SERVER2_HOST', '172.16.208.144' );
    define( 'PL2_SESS_SERVER2_PORT', 18891 );
}

define( 'PL2_VKEY_VALIDTIME', 1800 );
define( 'PL2_APPID_PORTAL', 15000101 );
define( 'PL2_APPID_CITY',   15000102 );
define( 'PL2_APPID_ACTION', 15000103 );
define( 'PL2_APPID_CAMPUS', 15000901 );

require_once('qp_oigw.api.php');

define('ERROR_UIN_CONFLICT',   1);

//ptlogin登录错误码
define('ERROR_PL_NEWSESSION', 11);
define('ERROR_PL_TIMEOUT',    12);

//ptlogin2登录错误码
define('ERROR_PL2_API_SES_INIT',   21);
define('ERROR_PL2_API_SES_VERIFY', 22);


/* {{{ function qp_get_pt2appid() */
/**
 * 根据当前域名取得对应的ptlogin2业务ID
 *
 * @return  int  appid
 */
function qp_get_pt2appid()
{
    $host = isset($_SERVER['HTTP_HOST'])? $_SERVER['HTTP_HOST']: '';
    switch($host)
    {
        case 'qzone.qq.com':
            $appid = PL2_APPID_PORTAL;
            break;
        case 'city.qzone.qq.com':
            $appid = PL2_APPID_CITY;
            break;
        case 'xiaoyou.qq.com':
        case 'pv.xiaoyou.qq.com':
        case 'img.xiaoyou.qq.com':
            $appid = PL2_APPID_CAMPUS;
            break;
        default:
            $appid = PL2_APPID_ACTION;
    }
    return $appid;
}
/* }}} */


/* {{{ function qp_ptsess_getuin() */
/**
 * check session key & get uin.(同时兼容第一代和第二代)
 *
 * @param   bool get_detail   Whether to get user detail info.
 * @param   int appid         Application ID.
 *
 * @return  int       >=10000  QQ uin number.   <10000  Error Code
 */
function qp_ptsess_getuin( $appid = 0, $verify = true )
{
	$ckuin  = empty($_COOKIE[QP_SESS_NAME_UIN])? '0': $_COOKIE[QP_SESS_NAME_UIN];
	$p = ord($ckuin[0]);
	$uin = ($p < 0x30 || $p > 0x39)? intval(substr($ckuin, 1)): intval($ckuin);
    $ret = 0;

    if ( !empty($uin) && $uin >= 10000 )
    {
        if ( $verify == true )
        {
            $skey = isset($_COOKIE[QP_SESS_NAME_KEY])? $_COOKIE[QP_SESS_NAME_KEY]: '';
            if ( !empty($skey) )
            {
                if ( strlen($skey) > 15 || substr($skey,0,1) == '@' )
                {
                    //取得ptlogin2业务ID
                    if ( $appid == 0 )
                    {
                        $appid = qp_get_pt2appid();
                    }
					
                    $ret = @qp_pt2sess_verify($uin, $skey, $appid, false, PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT, PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT);
                    //$ret = '12092269';
                } else {
                    $ret = qp_ptsess_verify( $uin, $skey, PL_SESS_SERVER1_HOST, PL_SESS_SERVER1_PORT );
                }
            }
        } else {
            $ret = $uin;
        }
    }
    return( $ret );
}
/* }}} */


/* {{{ function qp_ptsess_getinfo() */
/**
 * check session key & get uin.
 *
 * @param   int appid         Application ID.
 *
 * @return  array/false      current QQ info if session exists.
 */
function qp_ptsess_getinfo( $appid = 0 )
{
	$ckuin  = empty($_COOKIE[QP_SESS_NAME_UIN])? '0': $_COOKIE[QP_SESS_NAME_UIN];
	$p = ord($ckuin[0]);
	$uin = ($p < 0x30 || $p > 0x39)? intval(substr($ckuin, 1)): intval($ckuin);
    $ret = false;

    if ( !empty($uin) && $uin >= 10000 )
    {
        $skey = isset($_COOKIE[QP_SESS_NAME_KEY])? $_COOKIE[QP_SESS_NAME_KEY]: '';
        if ( !empty($skey) )
        {
                if ( strlen($skey) > 15 || substr($skey,0,1) == '@' )
            {
                //取得ptlogin2业务ID
                if ( $appid == 0 )
                {
                    $appid = qp_get_pt2appid();
                }

                $ret = @qp_pt2sess_verify($uin, $skey, $appid, true, PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT, PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT);
            } else {
                $ret = qp_ptsess_verify( $uin, $skey, PL_SESS_SERVER1_HOST, PL_SESS_SERVER1_PORT );
                if ( $ret >= 10000 )
                {
                    $ret = array();
                    $ret['uin'] = $uin;
                    $ret['nickname'] = qp_oigw_getnick();
                }
            }
        }
    }
    return( $ret );
}
/* }}} */


/* {{{ function qp_zzp_getuin() */
/**
 * Check zzpanel key & get uin.
 *
 * @return int  QQ uin number.
 */
function qp_zzp_getuin()
{
	$uin = isset($_COOKIE[QP_ZZPANEL_UIN])? intval($_COOKIE[QP_ZZPANEL_UIN]): 0;
    if ( $uin < 10000 )
    {
        return 0;
    }

    $ret = qp_zzp_verify( $uin, (isset($_COOKIE[QP_ZZPANEL_KEY])? $_COOKIE[QP_ZZPANEL_KEY]: '') );
    if ( $ret != 0 ) {
        $uin = 0;
    } // if

    return $uin;
}
/* }}} */



/* {{{ function qp_sess_getuin() */
/**
 * 当skey存在或zzpkey存在时或两者都存在并相等时返回相应uin, 当skey不等于zzpkey时返回zzpuin并由参数指定是否(默认是)干掉skey，其它情况返回0
 * @name qp_sess_getuin
 * @param bool[optional] $kill_ptsess
 * @return uin or 0
 */
function qp_sess_getuin($kill_ptsess = true)
{
    $uinz = qp_zzp_getuin();

    //先不通过服务器检验简单取uin
    $uins = qp_ptsess_getuin(0, false);

    if ( $uinz == $uins )
    {
        if ( $uinz == 0 )
        {
            return 0;
        } else {
            return $uinz;
        }
    } elseif ( $uinz == 0 ) {
        //通过服务器检验再取一次
        $uins = qp_ptsess_getuin();

        if ( $uins < 10000 )
        {
            return 0;
        } else {
			zzp_login($uins);
            return $uins;
        }
    } else {
        if ( $kill_ptsess )
        {
            //强制注销ptlogin session
            qp_ptsess_logout();
        }
        return $uinz;
    }
}
/* }}} */


/* {{{ function qp_sess_getuin2() */
/**
 * 增强的qp_sess_getuin, 失败时返回错误码而非统一的0
 * @name qp_sess_getuin2
 * @param bool[optional] $kill_ptsess
 * @return uin or 0
 */
function qp_sess_getuin2($kill_ptsess = true)
{
    $uinz = qp_zzp_getuin();

    //先不通过服务器检验简单取uin
    $uins = qp_ptsess_getuin(0, false);

    if ( $uinz == $uins )
    {
        if ( $uinz == 0 )
        {
            return 0;
        } else {
            return $uinz;
        }
    } elseif ( $uinz == 0) {
        //通过服务器检验再取一次
        $uins = qp_ptsess_getuin();
        return $uins;
    } elseif ( $uins == 0) {
        return $uinz;
    } else {
        if ( $kill_ptsess )
        {
            //强制注销ptlogin session
            qp_ptsess_logout();
        }
        return $uinz;
    }
}
/* }}} */


/* {{{ function qp_sess_getsafeuin() */
/**
 * 在skey等于zzpkey时返回uin, zzpkey不存在时由skey生成, 其它情况清除zzpkey并返回0
 * @name qp_sess_getsafeuin
 * @return uin or 0
 */
function qp_sess_getsafeuin()
{
    $uinz = qp_zzp_getuin();
    $uins = qp_ptsess_getuin();

    if ( $uinz == $uins )
    {
        if ( $uinz == 0 )
        {
            return 0;
        } else {
            return $uinz;
        }
    } elseif ( $uinz == 0 && $uins >= 10000 ) {

        zzp_login($uins);
        return $uins;
    } else {

        //干掉zzpanel key
        zzp_login();

        //干掉ptlogin session key
        qp_ptsess_logout();

        return 0;
    }
}
/* }}} */


/* {{{ function qp_sess_getsafeuin2() */
/**
 * 增强的qp_sess_getsafeuin, 失败时返回错误码而非统一的0
 * @name qp_sess_getsafeuin2
 * @return uin or 0
 */
function qp_sess_getsafeuin2()
{
    $uinz = qp_zzp_getuin();
    $uins = qp_ptsess_getuin();

    if ( $uinz == $uins )
    {
        if ( $uinz == 0 )
        {
            return 0;
        } else {
            return $uinz;
        }
    } elseif ( $uinz == 0 && $uins > 0) {

        if ( $uins >= 10000 )
        {
            zzp_login($uins);
        }
        return $uins;
    } else {

        //干掉zzpanel key
        zzp_login();

        //干掉ptlogin session key
        qp_ptsess_logout();

        return ERROR_UIN_CONFLICT;
    }
}
/* }}} */


/* {{{ function zzp_login() */
/**
 * 登录/注销zzpkey, uin为0时注销, 否则登录.(内部调用)
 *
 * @param  int  uin
 * @return  void
 */
function zzp_login($uin = 0)
{
    if ($uin == 0)
    {
        //干掉zzpanel key
        $_COOKIE[QP_ZZPANEL_UIN] = '';
        $_COOKIE[QP_ZZPANEL_KEY] = '';
        setcookie( QP_ZZPANEL_UIN, $_COOKIE[QP_ZZPANEL_UIN], $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );
        setcookie( QP_ZZPANEL_KEY, $_COOKIE[QP_ZZPANEL_KEY], $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );
    } else {
        $_COOKIE[QP_ZZPANEL_UIN] = $uin;
        $_COOKIE[QP_ZZPANEL_KEY] = qp_zzp_make($uin);
        setcookie( QP_ZZPANEL_UIN, $_COOKIE[QP_ZZPANEL_UIN], 0, '/', '.qq.com' );
        setcookie( QP_ZZPANEL_KEY, $_COOKIE[QP_ZZPANEL_KEY], 0, '/', '.qq.com' );
    }
}
/* }}} */


/* {{{ function qp_ptsess_logout() */
/**
 * Clear session & cookie.
 *
 * @param   int[optional] appid         Application ID.
*/
function qp_ptsess_logout($appid = 0)
{
	$ckuin  = empty($_COOKIE[QP_SESS_NAME_UIN])? '0': $_COOKIE[QP_SESS_NAME_UIN];
	$p = ord($ckuin[0]);
	$uin = ($p < 0x30 || $p > 0x39)? intval(substr($ckuin, 1)): intval($ckuin);
	$skey = isset($_COOKIE[QP_SESS_NAME_KEY])? $_COOKIE[QP_SESS_NAME_KEY]: '';

    if ( $uin > 10000 && strlen($skey) > 15 )
    {
        //取得ptlogin2业务ID
        if ( $appid == 0 )
        {
            $appid = qp_get_pt2appid();
        }

        qp_pt2sess_destory($uin, $skey, $appid, PL2_SESS_SERVER1_HOST, PL2_SESS_SERVER1_PORT, PL2_SESS_SERVER2_HOST, PL2_SESS_SERVER2_PORT);
    }

    $_COOKIE[QP_SESS_NAME_UIN]   = '';
    $_COOKIE[QP_SESS_NAME_KEY]   = '';
    setcookie( QP_SESS_NAME_UIN, $_COOKIE[QP_SESS_NAME_UIN], $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );
    setcookie( QP_SESS_NAME_KEY, $_COOKIE[QP_SESS_NAME_KEY], $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );
}
/* }}} */


/* {{{ function qp_redirect( $url ) */
/**
 * Re-direct to a URL & exit.
 *
 * @param   string  $url    redirect URL.
 */
function qp_redirect( $url )
{
    header( 'Location: '.$url );
    exit();
    return;
}
/* }}} */


/* {{{ function qp_vkey_verify() */
/**
 * 大图片验证码验证(同时兼容第一代和第二代)
 *
 * @param   string  vkey     vkey code input by user
 * @param   int[optional=0]  appid      Application ID.
 * @param   int[optional]    expire     expire time.
*/
function qp_vkey_verify($vkey, $appid = 0, $expire = PL2_VKEY_VALIDTIME)
{
    $sid = isset($_COOKIE['verifysession'])? $_COOKIE['verifysession']: '';
    //绿色环保,用了就删
    setcookie( 'verifysession', false, $_SERVER['REQUEST_TIME'] - 3600, '/', '.qq.com' );

    $i = strlen($sid);

    if ( strlen($vkey) < 4 || $i < 10 )
    {
        return false;
    } elseif ( $i <= 70 ) {
    	echo "b";
        return false; //@qp_imgkey_verify($sid, $vkey);
    } else {
        //取得ptlogin2业务ID
        if ( $appid == 0 )
        {
            $appid = qp_get_pt2appid();
        }

        return @qp_pt2vkey_verify($vkey, $sid, PL2_VKEY_VALIDTIME, $appid, PL2_VKEY_SERVER1_HOST, PL2_VKEY_SERVER1_PORT, PL2_VKEY_SERVER2_HOST, PL2_VKEY_SERVER2_PORT);
    }
}
/* }}} */

//End of script
