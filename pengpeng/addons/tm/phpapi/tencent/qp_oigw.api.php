<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | Tencent Qzone Protal PHP Library OIGW Library.                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007 Tencent Inc. All Rights Reserved.                 |
// +----------------------------------------------------------------------+
// | Authors: The Qzone Portal Dev Team, ISRD, Tencent Inc.               |
// |          Kulin <kulin@tencent.com>                                   |
// +----------------------------------------------------------------------+

/**
 * @version   0.2.0
 * @author    Kulin <kulin@tencent.com>
 * @date      2007-12-14 16:21
 * @brief.    QPLIB_OIGW
 */

require_once('qp_binary.inc.php');

//定义QQ在线状态
define( 'OIGW_QQ_STATE_OFFLINE', 0 );
define( 'OIGW_QQ_STATE_ONLINE',  1 );
define( 'OIGW_QQ_STATE_HIDDEN',  2 );

define( 'OIGW_FIELD_CMD',      'cmd' );
	define( 'OIGW_CMD_GETNICK',    'GETNICK' );
	define( 'OIGW_CMD_ONLINE',     'ONLINE' );
	define( 'OIGW_CMD_GETFACE',    'GETFACE' );
	define( 'OIGW_CMD_FACEFLAG',   'FACEFLAG' );
	define( 'OIGW_CMD_TIPS',       'NEWTIPS' );
	define( 'OIGW_CMD_FRIENDS',    'FRIENDS' );
	define( 'OIGW_CMD_VERIFYFRIEND',   'VERIFYFRIEND' );

define( 'OIGW_FIELD_APP',      'appname' );
define( 'OIGW_FIELD_UIN',      'uin' );
define( 'OIGW_FIELD_QQNUM',    'qqnumber' );
define( 'OIGW_FIELD_CLIENT',   'client_ip' );

define( 'OIGW_FIELD_RET',      'result' );
define( 'OIGW_FIELD_ERR',      'error' );
define( 'OIGW_FIELD_INFO',     'info' );
define( 'OIGW_FIELD_FACEFLAG', 'faceflag' );

//原会员组的服务器: 172.16.63.23 13104
define( 'QP_OIGW_HOST_GETNICK',   '172.16.63.23' );
define( 'QP_OIGW_PORT_GETNICK',   13104 );
define( 'QP_OIGW_HOST_ONLINE',    '172.16.63.23' );
define( 'QP_OIGW_PORT_ONLINE',    13104 );
define( 'QP_OIGW_HOST_GETFACE',   '172.16.63.23' );
define( 'QP_OIGW_PORT_GETFACE',   13104 );
define( 'QP_OIGW_HOST_FACEFLAG',  '172.16.63.23' );
define( 'QP_OIGW_PORT_FACEFLAG',  13104 );
define( 'QP_OIGW_APP',            'club' );

//原会员组的服务器: 172.16.63.23 13104
define( 'QP_OIGW_HOST_GETNICKS',    '172.16.57.180' );
define( 'QP_OIGW_PORT_GETNICKS',    31001 );
define( 'QP_OIGW_CMD_GETNICKS', 'cmd=BATCHNICK&appname=club&qqnumlist=' );

define( 'QP_OIGW_FRIENDLIST_HOST',    '172.16.57.180' );
define( 'QP_OIGW_FRIENDLIST_PORT',    31001 );

//功能性Tips服务器配置
global $OIGW_FUN_TIPS_SERVERS;
if ( !isset($OIGW_FUN_TIPS_SERVERS) )
{
	$OIGW_FUN_TIPS_SERVERS = array(
								array('host' => '172.16.48.238', 'port' => 8010),
								array('host' => '172.16.48.238', 'port' => 8011),
								array('host' => '172.16.48.238', 'port' => 8012),
								array('host' => '172.16.244.56', 'port' => 8010),
								array('host' => '172.16.244.56', 'port' => 8011),
								array('host' => '172.16.244.56', 'port' => 8012),
							);
}

//QQ群服务器配置
global $OIGW_GROUP_SERVERS;
if ( !isset($OIGW_GROUP_SERVERS) )
{
	$OIGW_GROUP_SERVERS = array(
								array('host' => '172.16.208.200', 'port' => 19000),
							);
}

//---------- 即通接口服务器配置 -------------
//Get Userinfo Detail
global $OIGW_SERVERS_GUD;
if ( !isset($OIGW_SERVERS_GUD) )
{
	$OIGW_SERVERS_GUD = array(
								array('host' => '172.23.4.126', 'port' => 33000),
							);
}

//---------- 即通接口服务器配置 -------------
//Get/Set User Richflag
global $OIGW_SERVERS_URF;
if ( !isset($OIGW_SERVERS_URF) )
{
	$OIGW_SERVERS_URF = $OIGW_SERVERS_GUD;
}

define('OIGW_RICH_FLAG_TYPE_CAMPUS', 103);

//QQ接口相关常数
define('OIGW_PKG_FIXED_SIZE', 129);
define('OIGW_STX', 2);
define('OIGW_VER', 500);
define('OIGW_ETX', 3);

define('OIGW_CMD_GET_USERINFO_DETAIL', 0x00);
define('OIGW_CMD_GET_USERINFO_SIMPLE', 0x43);
define('OIGW_CMD_GET_USER_RICHFLAG', 0x66);
define('OIGW_CMD_SET_USER_RICHFLAG', 0x34);

//QQ群相关常数
define('OIGW_GROUP_COMM_TIMEOUT', 2);
define('OIGW_GROUP_STX', 0x02);
define('OIGW_GROUP_ETX', 0x03);
define('OIGW_GROUP_PKG_FIXED_SIZE', 29);

define('OIGW_GROUP_CMD_GET_BY_CREATOR', 0x01);
define('OIGW_GROUP_CMD_GET_DETAIL',     0x03);
define('OIGW_GROUP_CMD_GET_MEMBERS',    0x04);
define('OIGW_GROUP_CMD_SEND_MESSAGE',   0x05);
define('OIGW_GROUP_CMD_ALTER_MEMBERS',	0x07);
	define('OIGW_GROUP_CMD_ALTER_MEMBERS_FADD',	0x01); //强制添加
	define('OIGW_GROUP_CMD_ALTER_MEMBERS_FDEL',	0x02); //强制删除
	define('OIGW_GROUP_CMD_ALTER_MEMBERS_ADD',	0x51); //通过群主号添加
	define('OIGW_GROUP_CMD_ALTER_MEMBERS_DEL',	0x52); //通过群主号删除
define('OIGW_GROUP_CMD_SET_DETAIL',		0x08);
	define('OIGW_GROUP_CMD_SET_DETAIL_BASE',	0x01);
	define('OIGW_GROUP_CMD_SET_DETAIL_EXTEND',	0x03);
define('OIGW_GROUP_CMD_SET_CARDS',      0x09);
define('OIGW_GROUP_CMD_GET_CARDS',      0x0a);
define('OIGW_GROUP_CMD_SET_IDENTITY',	0x0c);
	define('OIGW_GROUP_IDENTITY_NO_ADMIN',	0x00);
	define('OIGW_GROUP_IDENTITY_SET_ADMIN',	0x01);
	define('OIGW_GROUP_IDENTITY_NO_BOSS',	0x02);
	define('OIGW_GROUP_IDENTITY_SET_BOSS',	0x03);
define('OIGW_GROUP_CMD_GET_BY_UIN',     0x0d);
define('OIGW_GROUP_CMD_SPECIAL_MSG',    0x10);
	define('OIGW_GROUP_CMD_SPECIAL_MSG_INFO',	0x0C);
	define('OIGW_GROUP_CMD_SPECIAL_MSG_TAB',	0x08);
	define('OIGW_GROUP_CMD_SPECIAL_MSG_FACE',	0x09);
define('OIGW_GROUP_CMD_GAMEOVER',       0x12);

define('OIGW_GROUP_FLAG_DISABLED',     0x2);
define('OIGW_GROUP_FLAG_VIP',          0x10);
define('OIGW_GROUP_FLAG_ENT',          0x100);

define('OIGW_GROUP_MEMBER_FLAG_ADMIN',     0x1);
define('OIGW_GROUP_MEMBER_FLAG_BOSS',      0x2);
define('OIGW_GROUP_MEMBER_FLAG_VIP',       0x4);


class OIGWPacket extends BinaryPacket
{
    public $err_str = '';
	public $svctype = 0;

    function __construct($body = '')
    {
        parent::__construct($body);
    }

    public function request($server, $cmd, $uin)
    {
        $ip = '';//empty($_SERVER['REMOTE_ADDR'])? '': $_SERVER['REMOTE_ADDR'];
        $port = '';//empty($_SERVER['REMOTE_PORT'])? '': $_SERVER['REMOTE_PORT'];
        $pkglen = OIGW_PKG_FIXED_SIZE + strlen($this->body);

		$info = pack('na11a39Ca63', 0, 'kulin', '', $this->svctype, '');
		$pkg = pack('CnnnNC', OIGW_STX, $pkglen, OIGW_VER, $cmd, $uin, 0).$info;
        $pkg .= $this->body;
        $pkg .= chr(OIGW_ETX);

		try
        {
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if ( !$socket )
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

            //设置Socket为阻塞模式
            if (!socket_set_block($socket))
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

            //设置Socket的接收超时时间
            if (!socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0)))
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

			//发送包
            if (socket_sendto($socket, $pkg, $pkglen, 0, $server['host'], $server['port']) == false)
            {
                throw new Exception('Fail to send data.');
            }

            //读取开始位和长度
            $xlen = socket_recv($socket, $buf, 3, MSG_PEEK);

            if ( $xlen == false || $xlen < 3 )
                throw new Exception('Timeout to receive STX and '.Length.' field.');

            //解析开始位和长度
            $h1 = @unpack('Cstx/nlen', $buf);
            if ( $h1['stx'] != OIGW_STX )
            {
                throw new Exception('Received invalid STX Code. (expected '.OIGW_STX.' got '.$h1['stx'].')');
            }

            //读取余下数据
            $len = $h1['len'];
            $xlen = socket_recv($socket, $buf, $len, 0);
            if ( $xlen == false || $xlen < $len )
                throw new Exception('Timeout to receive data.');

            //解析数据
            $h1['len'] -= OIGW_PKG_FIXED_SIZE;
            $h2 = unpack('Cstx/nlen/nver/ncmd/Nuin/Cret/a116info/a'.$h1['len'].'body/Cetx', $buf);

			$h2['info'] = pack('a116', $h2['info']);
			$info = unpack('nver/a11caller/a39reseverd1/Csvctype/a63reseverd2', $h2['info']);
			$this->svctype = $info['svctype'];

            if ( $h2['cmd'] != $cmd )
            {
                throw new Exception('Received dismatch Command Code. (expected '.$cmd.' got '.$h2['cmd'].')');
            } else if ( $h2['etx'] != OIGW_ETX ) {
                throw new Exception('Received dismatch ETX Code. (expected '.OIGW_ETX.' got '.$h2['etx'].')');
            } else if ( $h2['ret'] != 0 ) {
                throw new Exception('Operation Failed. (got result '.$h2['ret'].')');
            }

            $this->body = $h2['body'];
            $ret = true;
        }
        catch (Exception $e)
        {
            trigger_error( $e->getMessage(), E_USER_WARNING );
            $ret = false;
        }

        if ( $socket )
            socket_close($socket);

        return $ret;
    }
}

/* {{{ function qp_oigw_get_userinfo2() */
/**
 * 获取一个用户的QQ详细资料
 *
 * @param   int     $uin    QQ uin.
 * @return	ok: array; fail: FALSE.
 */
function qp_oigw_get_userinfo2($uin)
{
	global $OIGW_SERVERS_GUD;
	$server = $OIGW_SERVERS_GUD[array_rand($OIGW_SERVERS_GUD)];

	$qp = new OIGWPacket();
	$ret = false;

	if ( $qp->request($server, OIGW_CMD_GET_USERINFO_DETAIL, $uin) )
    {
		$keys = array('uin', 'passwd', 'nickname', 'country', 'province', 'postcode', 'address', 'phone', 'birthyear', 'gender', 'realname', 'email', 'pagerprovider', 'stationname', 'stationno', 'pagerno', 'pagertype', 'occupation', 'homepage', 'author', 'icqno', 'icqpwd', 'face', 'gsm', 'secret', 'perinfo', 'city', 'secretemail', 'idcard', 'gsmtype', 'gsmopeninfo', 'contactopeninfo', 'college', 'xingzuo', 'shengxiao', 'bloodtype', 'disable', 'regtime', 'identity', 'service');
		$vals = explode("\x1E", $qp->body);
		$ret = array_combine($keys, $vals);

		$bts = array('', 'A', 'B', 'O', 'AB', '其它');
		$sxs = array('', '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
		$xzs = array('', '水瓶座', '双鱼座', '牧羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '魔羯座');

		$ret['xingzuo'] = $xzs[intval($ret['xingzuo'])];
		$ret['shengxiao'] = $sxs[intval($ret['shengxiao'])];
		$ret['bloodtype'] = $bts[intval($ret['bloodtype'])];
    }

	return $ret;
}
/* }}} */


/* {{{ function qp_oigw_get_richflag() */
/**
 * 获取一个用户的QQ某业务图标是否点亮
 *
 * @param   int     $uin		QQ uin.
 * @param   int     $svctype	ServiceType
 * @return	ok: array; fail: FALSE.
 */
function qp_oigw_get_richflag($uin, $svctype)
{
	global $OIGW_SERVERS_URF;
	$server = $OIGW_SERVERS_URF[array_rand($OIGW_SERVERS_URF)];

	$qp = new OIGWPacket();
	$ret = false;

	$qp->svctype = $svctype;
	if ( $qp->request($server, OIGW_CMD_GET_USER_RICHFLAG, $uin) )
    {
		$ret = ord($qp->body);
    }

	return $ret;
}
/* }}} */


/* {{{ function qp_oigw_set_richflag() */
/**
 * 点亮/熄灭用户QQ某业务图标
 *
 * @param   int			$uin		QQ uin.
 * @param   int			$svctype	ServiceType
 * @param   [bool]		$turnon		true点亮，false熄灭
 * @param   [string]	$desc		业务描述,不能为空
 * @return	ok: array; fail: FALSE.
 */
function qp_oigw_set_richflag($uin, $svctype, $turnon, $desc)
{
	global $OIGW_SERVERS_URF;
	$server = $OIGW_SERVERS_URF[array_rand($OIGW_SERVERS_URF)];
	$ret = false;

	$qp = new OIGWPacket();
	$qp->svctype = $svctype;
	$qp->addUChar($svctype);
	$qp->addShortString($desc);
	$qp->addUChar($turnon? 1: 0);

	if ( $qp->request($server, OIGW_CMD_SET_USER_RICHFLAG, $uin) )
    {
		$ret = true;
    }

	return $ret;
}
/* }}} */


/* {{{ function qp_oigw_getnick() */
/**
 * Get QQ nickname.
 *
 * @param   int     $uin    QQ uin.
 * @param   string  $host   OIGW APP name.
 * @param   string  $host   OIGW host.
 * @param   int     $port   OIGW port.
 * @return                  nickname; NULL: fail.
 */
function qp_oigw_getnick( $uin,
                        $app  = QP_OIGW_APP,
                        $host = QP_OIGW_HOST_GETNICK,
                        $port = QP_OIGW_PORT_GETNICK )
{
    global $g_arr_nick;
    $info = array( OIGW_FIELD_CMD   => OIGW_CMD_GETNICK,
                   OIGW_FIELD_APP   => $app,
                   OIGW_FIELD_QQNUM => $uin );

    if ( @isset( $g_arr_nick[$uin] ) ) {
        return( $g_arr_nick[$uin] );
    } // if

    $str_cmd = qp_build_str( $info );

    $sock = new qp_socket( $host, $port );
    $sock->set_eol( "\n" );
    $sock->connect();
    $sock->write_line( $str_cmd );
    $str_ack = $sock->read_line();
    $sock->close();

    $ack = qp_parse_str( $str_ack );
    if ( @strlen($ack[OIGW_FIELD_RET]) <= 0 ) {
        $ret = '';
    } else {
        $ret = $ack[OIGW_FIELD_RET];
        if ( $ret != 0 ) {
            $ret = '';
        } else {
            $ret = @$ack[OIGW_FIELD_INFO];
            if ( $ret == '' ) //如果昵称为空,则变为QQ号
			{
                $ret = $uin;
			}

			//转成utf-8
			$ret = output_encoding($ret);

            $g_arr_nick[$uin] = $ret;
        } // if
    } // if

    return( $ret );
}
/* }}} */

/* {{{ function qp_oigw_online() */
/**
 * Check QQ online status.
 *
 * @param   int     $uin    QQ uin.
 * @param   string  $host   OIGW APP name.
 * @param   string  $host   OIGW host.
 * @param   int     $port   OIGW port.
 * @return                  1: online; 2: hide; 0: offline; other: fail.
 */
function qp_oigw_online( $uin,
                      $app  = QP_OIGW_APP,
                      $host = QP_OIGW_HOST_ONLINE,
                      $port = QP_OIGW_PORT_ONLINE )
{
    $info = array( OIGW_FIELD_CMD   => OIGW_CMD_ONLINE,
                   OIGW_FIELD_APP   => $app,
                   OIGW_FIELD_QQNUM => $uin );

    $str_cmd = qp_build_str( $info );

    $sock = new qp_socket( $host, $port );
    $sock->set_eol( "\n" );
    $sock->connect();
    $sock->write_line( $str_cmd );
    $str_ack = $sock->read_line();
    $sock->close();

    $ack = qp_parse_str( $str_ack );
    if ( @strlen($ack[OIGW_FIELD_RET]) <= 0 ) {
        $ret = -1;
    } else {
        $ret = $ack[OIGW_FIELD_RET];
        if ( $ret != 0 ) {
            $ret = -1;
        } else {
            switch ( $ack[OIGW_FIELD_INFO] ) {
            case 'online':
                $ret = OIGW_QQ_STATE_ONLINE;
                break;
            case 'hide':
                $ret = OIGW_QQ_STATE_HIDDEN;
                break;
            case 'offline':
            default:
                $ret = OIGW_QQ_STATE_OFFLINE;
                break;
            } // switch
        } // if
    } // if

    return( $ret );
}
/* }}} */

/* ----------------------------------------------------------------------------------*/
/* {{{ function qp_oigw_getface() */
/**
 * Get QQ face.
 *
 * @param   int     $uin    QQ uin.
 * @param   string  $host   OIGW APP name.
 * @param   string  $host   OIGW host.
 * @param   int     $port   OIGW port.
 * @return                  >0: face ID; other: fail.
 */
function qp_oigw_getface( $uin,
                       $app  = QP_OIGW_APP,
                       $host = QP_OIGW_HOST_GETFACE,
                       $port = QP_OIGW_PORT_GETFACE )
{
	$info = array( OIGW_FIELD_CMD   => OIGW_CMD_GETFACE,
                   OIGW_FIELD_APP   => $app,
                   OIGW_FIELD_QQNUM => $uin );

    $str_cmd = qp_build_str( $info );

    $sock = new qp_socket( $host, $port );
    $sock->set_eol( "\n" );
    $sock->connect();
    $sock->write_line( $str_cmd );
    $str_ack = $sock->read_line();
    $sock->close();

    $ack = qp_parse_str( $str_ack );
    if ( @strlen($ack[OIGW_FIELD_RET]) <= 0 ) {
        $ret = -1;
    } else {
        $ret = $ack[OIGW_FIELD_RET];
        if ( $ret != 0 ) {
            $ret = 0;
        } else {
            $ret = @$ack[OIGW_FIELD_INFO];
            $ret = round( $ret / 3 ) + 1;
        } // if
    } // if

    return( $ret );
}
/* }}} */

/* ----------------------------------------------------------------------------------*/

/* {{{ function qp_oigw_friends() */
/**
 * Get QQ's friends.
 *
 * @param   array   $friends    Friends array. = array( 'count' => xx,
                                                        'list'  => array( 0 => xxx,
                                                                          1 => xxx, ... ); .
 * @param   int     $uin        QQ uin.
 * @param   string  $host       OIGW host.
 * @param   int     $port       OIGW port.
 * @param   string  $host       OIGW APP name.
 * @return                      0: succ, other: fail.
 */
function qp_oigw_friends( &$friends,
                            $uin = 0,
                            $host = QP_OIGW_HOST_GETNICK,
                            $port = QP_OIGW_PORT_GETNICK,
                            $app  = QP_OIGW_APP )
{
	//$uin 为0表示是只传一个参数并返回数组的形式
	$info = array( OIGW_FIELD_CMD   => OIGW_CMD_FRIENDS,
                   OIGW_FIELD_APP   => $app,
                   OIGW_FIELD_QQNUM => ($uin==0? intval($friends): $uin) );

    $str_cmd = qp_build_str( $info );

    $sock = new qp_socket( $host, $port );
    $sock->set_eol( "\n" );
    $sock->connect();
    $sock->write_line( $str_cmd );
    $str_ack = $sock->read_line();
    $sock->close();

    $ack = qp_parse_str( $str_ack );
    if ( @strlen($ack[OIGW_FIELD_RET]) <= 0 ) {
        $ret = $uin==0? false: -2;
    } else if ( @$ack[OIGW_FIELD_RET] == 0 ) {
		$ret = isset($ack[OIGW_FIELD_INFO])? explode( ',', $ack[OIGW_FIELD_INFO] ): array();
		if ( $uin != 0 )
		{
			$friends = array('count' => count($ret), 'list' => $ret);
			$ret = 0;
		}
    } else {
        $ret = $uin==0? false: @$ack[OIGW_FIELD_RET];
    } // if

    return( $ret );
}
/* }}} */

/* ----------------------------------------------------------------------------------*/

/* {{{ function qp_oigw_get_friends() */
function qp_oigw_get_friends( $uin, $group = false )
{
    $friends = array();

    $sock = new qp_socket( QP_OIGW_FRIENDLIST_HOST, QP_OIGW_FRIENDLIST_PORT );
    $sock->connect();

    $cmd_data = array(
        'cmd'      => 'GETGROUPINFO',
        'appname'  => 'club',
        'qqnumber' => $uin,
        'from'     => 'from'
    );
    $cmd = qp_build_str( $cmd_data );
    $sock->write_line( $cmd );

    $result = $sock->read_line();
    $sock->close();
    parse_str( $result, $res_hash );
    if ( isset( $res_hash['result'] ) && isset($res_hash['info']) && $res_hash['result'] === '0' )
	{
        $regex = '/([^:]*):([^|]+)/si';
        preg_match_all( $regex, trim($res_hash['info']), $matches );
        $count_matches = count($matches[0]);
        for ( $i = 0; $i < $count_matches; $i++ )
		{
            $group_name = @$matches[1][$i];
            if ( isset($group_name{0}) && $group_name{0} == '|' ) {
                $group_name = substr( $group_name, 1 );
            }
            $friends[$group_name] = @explode( ',', @$matches[2][$i] );
        }
    }

	if ( $group )
	{
		return $friends;
	} else {
		$fs = array();
		foreach($friends as $fg)
		{
			$fs = array_merge($fs, $fg);
		}
		sort($fs, SORT_NUMERIC);
		return $fs;
	}
}
/* }}} */

/* ----------------------------------------------------------------------------------*/

/* {{{ function qp_oigw_getnicks() */
function qp_oigw_getnicks($uins)
{
    if ( empty($uins) || !is_array($uins) )
    {
        return false;
    }

    $nicknames = array();
    $offset = 0;
    $page_rows = 50;

    $total_rows = count( $uins );
    $total_pages = ceil( $total_rows / $page_rows );

    for ( $page = 1; $page <= $total_pages; $page++ ) {

        $offset = ( $page - 1 ) * $page_rows;
        $batch_numbers = array_slice( $uins, $offset, $page_rows );
        if ( empty($batch_numbers) ) {
            continue;
        }

		sort($batch_numbers, SORT_NUMERIC);
        $batch_num_list = implode( ',', $batch_numbers );

        $sock = new qp_socket( QP_OIGW_HOST_GETNICKS, QP_OIGW_PORT_GETNICKS );
        $sock->connect();

        $cmd = QP_OIGW_CMD_GETNICKS.$batch_num_list;
        $sock->write_line( $cmd );
        $result = $sock->read_line();
        $sock->close();

        $res_hash = array();
        $tok = strtok( $result, '&' );
        while( $tok !== false ) {
            $tmp = explode( '=', $tok );
            if ( !$tmp ) {
                continue;
            }
            $res_hash[@$tmp[0]] = @$tmp[1];
            $tok = strtok( '&' );
        }

        if ( isset( $res_hash['result'] ) && $res_hash['result'] === '0' ) {
            $batch_nicknames = @explode( ',', @$res_hash['info'] );
            $count_batch_nicknames = count( $batch_nicknames );
            for ( $i = 0; $i < $count_batch_nicknames; $i++ ) {
                $nicknames[@$batch_numbers[$i]] = urldecode($batch_nicknames[$i]);
            }
        }
    }

	return output_encoding($nicknames);
}
/* }}} */

/* ----------------------------------------------------------------------------------*/

/* {{{ function qp_oigw_fun_tips() */
/**
 * 推送功能性Tips
 *
 * @param	int		$uin		目标用户QQ号码
 * @param	int		$suin		发送方QQ号码
 * @param	int		$msgtype	信息类型(向有关部门申请后得到)
 * @param	int		$msgid		信息ID(向有关部门申请后得到)
 * @param	string	$vcode		验证码ID(向有关部门申请后得到)
 * @param	array	$varmsg		内容信息(向有关部门申请后得到)
 * @return  true: ok; false: fail.
 */
function qp_oigw_fun_tips($uin, $suin, $msgtype, $msgid, $vcode, $varmsg)
{
	global $OIGW_FUN_TIPS_SERVERS;
	$server = $OIGW_FUN_TIPS_SERVERS[array_rand($OIGW_FUN_TIPS_SERVERS)];
	if ( empty($server) )
	{
		return false;
	}

	$errno = 0;
	$errstr = '';
	$timeout = 1;
	$socket = stream_socket_client('udp://'.$server['host'].':'.$server['port'], $errno, $errstr, $timeout);

	if ( !$socket || !is_array($varmsg) )
	{
		return false;
	} else {

		$flag = ((count($varmsg) > 0)? 1: 0);
		$data = sprintf('%.1s%010.10s%010.10s%4.4s', $flag, $msgid, $msgid, $vcode);

		$vars = '';
		$varslen = 0;
		foreach ( $varmsg as $value )
		{
			$n = strlen($value);
			if ( $n > 255 )
			{
				fclose($socket);
				return false;
			}

			$varslen += (1 + $n);
			$vars .= pack('Ca*', $n, $value);
		}

		$ip = '';
		$port = '';
		$seqnum = 100;
		$suin = sprintf('%010u', $suin);
		$uin = sprintf('%010u', $uin);
		$body = pack('nna10a10a*na*', $msgtype, $seqnum, $suin, $uin, $data, $varslen, $vars );

		//29 = 53 - 24，53是除去body后数据包其它字段的总长度，24是body中sysmsg域的长度（参见tips发送协议）
		$len = strlen($body) + 29;
		$head = pack('nCna16a6', $len, 1, 0, $ip, $port);

		$pkg = pack('Ca*a*C', 2, $head, $body, 3);

		stream_set_timeout($socket, $timeout);
		fwrite($socket, $pkg);

		//目前暂时无回包
		fclose($socket);

		return true;
	}
}
/* }}} */

function qp_oigw_tips( $uin, $title, $body, $url )
{
	return qp_oigw_fun_tips($uin, $uin, 0x4c, 4182, 'EFAA', array('32Char='.$title, '128Char='.$url, '128Char='.$body));
}


class QQGroupPacket extends BinaryPacket
{
    public $err_str = '';

    function __construct($body = '')
    {
        parent::__construct($body);
    }

    public function request($cmd)
    {
		global $OIGW_GROUP_SERVERS;

        static $seq = 0;
        //$seq++; //暂不使用

        $ip = '';//empty($_SERVER['REMOTE_ADDR'])? '': $_SERVER['REMOTE_ADDR'];
        $port = '';//empty($_SERVER['REMOTE_PORT'])? '': $_SERVER['REMOTE_PORT'];
        $pkglen = OIGW_GROUP_PKG_FIXED_SIZE + strlen($this->body);
        $pkg = pack('CnCna16a6', OIGW_GROUP_STX, $pkglen, $cmd, $seq, $ip, $port);
        $pkg .= $this->body;
        $pkg .= chr(OIGW_GROUP_ETX);

        try
        {
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if ( !$socket )
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

            //设置Socket为阻塞模式
            if (!socket_set_block($socket))
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

            //设置Socket的接收超时时间
            if (!socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0)))
            {
                throw new Exception(socket_last_error().' - '.socket_strerror());
            }

			if ( empty($OIGW_GROUP_SERVERS) )
			{
				return;
			}

			$server = $OIGW_GROUP_SERVERS[array_rand($OIGW_GROUP_SERVERS)];

			//发送包
            if (socket_sendto($socket, $pkg, $pkglen, 0, $server['host'], $server['port']) == false)
            {
                throw new Exception('Fail to send data.');
            }

            //读取开始位和长度
            $xlen = socket_recv($socket, $buf, 3, MSG_PEEK);
            if ( $xlen == false || $xlen < 3 )
                throw new Exception('Timeout to receive STX and Length field.');

            //解析开始位和长度
            $h1 = @unpack('Cstx/nlen', $buf);
            if ( $h1['stx'] != OIGW_GROUP_STX )
            {
                throw new Exception('Received invalid STX Code. (expected '.OIGW_GROUP_STX.' got '.$h1['stx'].')');
            }

            //读取余下数据
            $len = $h1['len'];
            $xlen = socket_recv($socket, $buf, $len, 0);
            if ( $xlen == false || $xlen < $len )
                throw new Exception('Timeout to receive data.');

            //解析数据
            $h1['len'] -= OIGW_GROUP_PKG_FIXED_SIZE;
            $h2 = @unpack('Ccmd/nseq/a16ip/a6port/a'.$h1['len'].'body/Cetx', substr($buf,3));
            if ( $h2['cmd'] != $cmd )
            {
                throw new Exception('Received dismatch Command Code. (expected '.$cmd.' got '.$h2['cmd'].')');
            } else if ( $h2['seq'] != $seq ) {
                throw new Exception('Received dismatch Sequeue Code. (expected '.$seq.' got '.$h2['seq'].')');
            } else if ( $h2['etx'] != OIGW_GROUP_ETX ) {
                throw new Exception('Received dismatch ETX Code. (expected '.OIGW_GROUP_ETX.' got '.$h2['etx'].')');
            }

            $this->body = $h2['body'];
            $ret = true;
        }
        catch (Exception $e)
        {
            trigger_error( $e->getMessage(), E_USER_WARNING );
            $ret = false;
        }

        if ( $socket )
            socket_close($socket);

        return $ret;
    }

    public function parseFlags($flags)
    {
        $ret = array();
        $ret['disabled'] = (($flags & OIGW_GROUP_FLAG_DISABLED) == OIGW_GROUP_FLAG_DISABLED);
        $ret['vip']      = (($flags & OIGW_GROUP_FLAG_VIP     ) == OIGW_GROUP_FLAG_VIP     );
        $ret['ent']      = (($flags & OIGW_GROUP_FLAG_ENT     ) == OIGW_GROUP_FLAG_ENT     );
        return $ret;
    }

    public function parseOptions($option)
    {
        $options = array('', 'accept', 'verify', 'refuse');

        if ( $option > count($options) )
            $option = 0;

        return $options[$option];
    }

    public function parseMemberFlags($flags)
    {
        $ret = array();
        $ret['admin'] = (($flags & OIGW_GROUP_MEMBER_FLAG_ADMIN ) == OIGW_GROUP_MEMBER_FLAG_ADMIN );
        $ret['boss']  = (($flags & OIGW_GROUP_MEMBER_FLAG_BOSS  ) == OIGW_GROUP_MEMBER_FLAG_BOSS  );
        $ret['vip']   = (($flags & OIGW_GROUP_MEMBER_FLAG_VIP   ) == OIGW_GROUP_MEMBER_FLAG_VIP   );
        return $ret;
    }
}


/* {{{ function qp_oigw_get_mygroups() */
/**
 * 取一个用户创建的QQ群列表
 *
 * @param   int    $qq    QQ uin
 * @return  array: ok; false: fail.
 */
function qp_oigw_get_mygroups($qq)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($qq);
    if ( $qg->request(OIGW_GROUP_CMD_GET_BY_CREATOR) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            $groups = array();
            $qq2 = $qg->getUInt();
            $count = $qg->getUShort();
            for( $i=0; $i<$count; $i++)
            {
                $guin = $qg->getUInt();
                $gcode = $qg->getUInt();

                $groups[$guin] = $gcode;
            }

            return $groups;
        } else {
            trigger_error( $qg->getReset(), E_USER_WARNING );
        }
    }

    //失败
    return false;
}
/* }}} */


/* {{{ function qp_oigw_get_groups() */
/**
 * 取一个用户创建或加入的QQ群列表
 *
 * @param   int    $qq    QQ uin
 * @return  array: ok; false: fail.
 */
function qp_oigw_get_groups($qq)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($qq);
    if ( $qg->request(OIGW_GROUP_CMD_GET_BY_UIN) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            $groups = array();
            $qq2 = $qg->getUInt();
            $count = $qg->getUShort();
            for( $i=0; $i<$count; $i++)
            {
                $guin = $qg->getUInt();
                $gcode = $qg->getUInt();

                $groups[$guin] = $gcode;
            }

            return $groups;
        } else {
            trigger_error( $qg->getReset(), E_USER_WARNING );
        }
    }

    //失败
    return false;
}
/* }}} */


/* {{{ function qp_oigw_group_get_detail() */
/**
 * 取一个QQ群的基本资料及成员信息
 *
 * @param   int    $gcode    群外部号码
 * @return  array: ok; false: fail.
 */
function qp_oigw_group_get_detail($gcode, $raw_output = false, $ver = 0)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUChar($ver);
    if ( $qg->request(OIGW_GROUP_CMD_GET_DETAIL) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            //请求成功
            $group = array();

            $group['uin'] = $qg->getUInt();
            $group['code'] = $qg->getUInt();
            $group['type'] = $qg->getUChar()==1? 'fixed': 'temp';
            $group['createtime'] = $qg->getInt();
            $group['flags'] = $qg->getUInt();
            $group['owneruin'] = $qg->getUInt();
            $group['option'] = $qg->getUChar();
            $group['class'] = $qg->getUInt();

            if ( $ver >= 1 )
            {
                $group['classex'] = $qg->getUInt();
                $group['maxmember'] = $qg->getUShort();
            }

            if ( $ver >= 2 )
            {
                $group['spclass'] = $qg->getUShort();
            }

            $group['infoseq'] = $qg->getUInt();
            $group['name'] = output_encoding($qg->getString());
            $group['faceid'] = $qg->getUShort();
            $group['notice'] = output_encoding($qg->getString());
            $group['desc'] = output_encoding($qg->getString());

            $group['members'] = array();
            $mcount = $qg->getUShort();

			if ( $raw_output )
			{
				for ( $i=0; $i<$mcount; $i++ )
				{
					$qq = $qg->getUInt();
					$flags = $qg->getUChar();
					$group['members'][$qq]['flags'] = $flags;
				}
			} else {
				$group['flags'] = $qg->parseFlags( $group['flags'] );
				$group['option'] = $qg->parseOptions( $group['option'] );

				for ( $i=0; $i<$mcount; $i++ )
				{
					$qq = $qg->getUInt();
					$flags = $qg->parseMemberFlags( $qg->getUChar() );
					$group['members'][$qq]['flags'] = $flags;
				}
			}

            return $group;
        } else if ( $result == 2 ) {
            //群不存在
            return array();
        } else {
            trigger_error( $qg->getReset(), E_USER_WARNING );
        }
    }

    //失败
    return false;
}
/* }}} */


/* {{{ function qp_oigw_get_group_members() */
/**
 * 取一个QQ群的成员的QQ信息或群名片信息
 *
 * @param   int    $gcode    群外部号码
 * @param   bool   $card     指明取QQ资料还是群名片资料
 * @return  array: ok; false: fail.
 */
function qp_oigw_group_get_members($gcode, $card = false)
{
    $qg = new QQGroupPacket();

    if ( $card )
    {
        //取群名片资料
        $nextid = 0;
        $members = array();
        do
        {
            $qg->addUInt(0);
            $qg->addUInt($gcode);
            $qg->addUInt($nextid);
            if ( $qg->request(OIGW_GROUP_CMD_GET_CARDS) )
            {
                $result = $qg->getUChar();
                if ( $result == 0 )
                {
                    //请求成功
                    $guin = $qg->getUInt();
                    $gcode = $qg->getUInt();
                    $nextid = $qg->getUInt();

                    for ( $i=0; strlen($qg->body) >= 5; $i++ )
                    {
                        $member = array();
                        $member['uin'] = $qg->getUInt();
                        $member['name'] = output_encoding($qg->getString());
                        $members[] = $member;
                    }
                } else {
                    //失败
                    trigger_error( $qg->getReset(), E_USER_WARNING );
                    return false;
                }
            }
        }while($nextid != 0);

        return $members;

    } else {
        //取普通资料
        $qg->addUInt($gcode);
        if ( $qg->request(OIGW_GROUP_CMD_GET_MEMBERS) )
        {
            $result = $qg->getUChar();
            if ( $result == 0 )
            {
                //请求成功
                $guin = $qg->getUInt();
                $gcode = $qg->getUInt();

                $members = array();
                for ( $i=0; strlen($qg->body) >= 11; $i++ )
                {
                    $member = array();
                    $member['uin'] = $qg->getUInt();
                    $member['faceid'] = $qg->getUShort();
                    $member['age'] = $qg->getUChar();
                    $member['gender'] = $qg->getUChar()? '女': '男';
                    $member['nick'] = output_encoding($qg->getString());
					$flag = $qg->getUShort();
                    $member['vip'] = ($flag & 0x4)? TRUE: FALSE;
                    $members[] = $member;
                }

                return $members;
            } else if ( $result == 2 ) {
                //群不存在
                return array();
            } else {
                //失败
                trigger_error( $qg->getReset(), E_USER_WARNING );
                return false;
            }
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_sendmsg() */
/**
 * 模拟群中某个用户在QQ群发送一条消息
 *
 * @param   int     $gcode    群外部号码
 * @param   int     $suin     发送人的QQ号
 * @param   string  $msg      信息内容
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_sendmsg($gcode, $suin, $msg)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUInt($suin);
    $qg->addString($msg);
    if ( $qg->request(OIGW_GROUP_CMD_SEND_MESSAGE) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 || $result == 3 ) {
            //群不存在或不是群成员
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_add_members() */
/**
 * 添加群成员
 *
 * @param   int				$gcode    群外部号码
 * @param   int | array     $uins     要添加的QQ号列表
 * @param   int[optional]	$suin     群主号码,指定此参数将以群主身份进行操作
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_add_members($gcode, $uins, $opuin = 0)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($gcode);
	if ( $opuin == 0 )
	{
	    $qg->addUChar(OIGW_GROUP_CMD_ALTER_MEMBERS_FADD);
	} else {
	    $qg->addUChar(OIGW_GROUP_CMD_ALTER_MEMBERS_ADD);
		//指定群主号
	    $qg->addUInt($opuin);
	}

	//如果是0或是空数组都返回失败
	if ( $uins == false )
	{
		return false;
	}

	if ( is_array($uins) )
	{
		foreach ($uins as $uin)
		{
			$qg->addUInt($uin);
		}
	} else {
		$qg->addUInt($uins);
	}

    if ( $qg->request(OIGW_GROUP_CMD_ALTER_MEMBERS) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 3 ) {
            //群不存在或不是群成员
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_del_members() */
/**
 * 添加群成员
 *
 * @param   int				$gcode    群外部号码
 * @param   int | array     $uins     要添加的QQ号列表
 * @param   int[optional]	$suin     群主号码,指定此参数将以群主身份进行操作
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_del_members($gcode, $uins, $opuin = 0)
{
    $qg = new QQGroupPacket();
    $qg->addUInt($gcode);
	if ( $opuin == 0 )
	{
	    $qg->addUChar(OIGW_GROUP_CMD_ALTER_MEMBERS_FDEL);
	} else {
	    $qg->addUChar(OIGW_GROUP_CMD_ALTER_MEMBERS_DEL);
		//指定群主号
	    $qg->addUInt($opuin);
	}

	//如果是0或是空数组都返回失败
	if ( $uins == false )
	{
		return false;
	}

	if ( is_array($uins) )
	{
		foreach ($uins as $uin)
		{
			$qg->addUInt($uin);
		}
	} else {
		$qg->addUInt($uins);
	}

    if ( $qg->request(OIGW_GROUP_CMD_ALTER_MEMBERS) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 3 ) {
            //群不存在或不是群成员
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_set_detail() */
/**
 * 添加群成员
 *
 * @param   int				$gcode		群外部号码
 * @param   array			$detail		要更改的基本资料
 * @param   array			$extinfo	要改的扩展资料
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_set_detail($gcode, $detail, $extinfo = FALSE)
{
	if ( $gcode == FALSE || ($detail == FALSE && $extinfo == FALSE) )
	{
		return false;
	}

	$ret = true;
	if ( is_array($detail) )
	{
		$org = qp_oigw_group_get_detail($gcode, true);
		if ( $org == false )
		{
			return false;
		}

		$qg = new QQGroupPacket();
		$qg->addUInt($gcode);
		$qg->addUChar(OIGW_GROUP_CMD_SET_DETAIL_BASE);
		$qg->addUChar($org['option']);
		$qg->addUInt($org['class']);
		$qg->addShortString( input_encoding(isset($detail['name'])? $detail['name']: $org['name']) );
		$qg->addUShort( $org['faceid'] );
		$qg->addShortString( input_encoding(isset($detail['notice'])? $detail['notice']: $org['notice']) );
		$qg->addShortString( input_encoding(isset($detail['desc'])? $detail['desc']: $org['desc']) );

		if ( $qg->request(OIGW_GROUP_CMD_SET_DETAIL) )
		{
			$result = $qg->getUChar();
			if ( $result == 0 )
			{
				$ret = true;
				unset($qg);
			} else if ( $result == 2 || $result == 3 ) {
				//群不存在或不是群成员
				return null;
			} else {
				//失败
				trigger_error( $qg->getReset(), E_USER_WARNING );
				return false;
			}
		}
	}

	if ( $ret && is_array($extinfo) )
	{
		$qg = new QQGroupPacket();
		$qg->addUInt($gcode);
		$qg->addUChar(OIGW_GROUP_CMD_SET_DETAIL_EXTEND);

		$mask_flag = (isset($extinfo['flag']) && isset($extinfo['flagmask']))? 0x01: 0;
		$mask_clsext = (isset($extinfo['classext']))? 0x02: 0;
		$mask_mmax = (isset($extinfo['membermax']))? 0x04: 0;
		$mask_spcls = (isset($extinfo['specialclass']))? 0x08: 0;
		$mask = $mask_flag | $mask_clsext | $mask_mmax | $mask_spcls;

		if ( $mask == 0 )
		{
			return false;
		}
		$qg->addUInt($mask);

		if ( $mask_flag )
		{
			$qg->addUInt($extinfo['flag']);
			$qg->addUInt($extinfo['flagmask']);
		}

		if ( $mask_clsext )
		{
			$qg->addUInt($extinfo['classext']);
		}

		if ( $mask_mmax )
		{
			$qg->addUShort($extinfo['membermax']);
		}

		if ( $mask_spcls )
		{
			$qg->addUShort($extinfo['specialclass']);
		}

		if ( $qg->request(OIGW_GROUP_CMD_SET_DETAIL) )
		{
			$result = $qg->getUChar();
			if ( $result == 0 )
			{
				return true;
			} else if ( $result == 2 ) {
				//群不存在
				return null;
			} else {
				//失败
				trigger_error( $qg->getReset(), E_USER_WARNING );
				return false;
			}
		}
	}
}
/* }}} */


/* {{{ function qp_oigw_group_set_identity() */
/**
 * 变更某人的群管理员身份
 *
 * @param   int				$gcode	群外部号码
 * @param   int				$qq		要变更用户QQ号码
 * @param   int				$flag	OIGW_GROUP_IDENTITY_* 类常数 0 - 取消普通管理员；1 - 设置普通管理员；2 - 取消特殊管理员；3 - 设置特殊管理员
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_set_identity($gcode, $qq, $flag)
{
	$flag = intval($flag);
	if ( $gcode == false || $qq < 10000 || $flag > 3 )
	{
		return false;
	}

    $qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUInt($qq);
    $qg->addUChar($flag);

    if ( $qg->request(OIGW_GROUP_CMD_SET_IDENTITY) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 || $result == 3 ) {
            //群不存在或不是群成员
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_set_cards() */
/**
 * 变更用户的群名片
 *
 * @param   int				$gcode	群外部号码
 * @param   array			$users	要变更用户QQ号码(单个或多个)
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_set_cards($gcode, $users)
{
	$user = reset($users);
	if ( !is_array($user) )
	{
		$users = array($users);
	}

    $qg = new QQGroupPacket();
    $qg->addUInt(0);
    $qg->addUInt($gcode);
    $qg->addUInt(1);

	foreach($users as $user)
	{
		if ( empty($user['uin']) || $user['uin'] < 10000 )
		{
			continue;
		}

	    $qg->addUInt($user['uin']);

		$flag = 0;
		if ( isset($user['name']) ) $flag |= 0x1;
		if ( isset($user['gender']) ) $flag |= 0x2;
		if ( isset($user['phone']) ) $flag |= 0x4;
		if ( isset($user['email']) ) $flag |= 0x8;
		if ( isset($user['remark']) ) $flag |= 0x10;
	    $qg->addUInt($flag);

		if ( isset($user['name']) ) $qg->addShortString(input_encoding($user['name']));
		if ( isset($user['gender']) ) $qg->addUChar(input_encoding($user['gender']));
		if ( isset($user['phone']) ) $qg->addShortString(input_encoding($user['phone']));
		if ( isset($user['email']) ) $qg->addShortString(input_encoding($user['email']));
		if ( isset($user['remark']) ) $qg->addShortString(input_encoding($user['remark']));

	}

    if ( $qg->request(OIGW_GROUP_CMD_SET_CARDS) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 || $result == 3 ) {
            //群不存在或不是群成员
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_gameover() */
/**
 * 解散一个群
 *
 * @param   int				$gcode			群外部号码
 * @param   [bool]			$noblack = 1	忽略黑名单群
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_gameover($gcode, $noblack = 1)
{
    //加日志
	$de = ini_get('display_errors');
	ini_set('display_errors', 0);
	trigger_error( '注意,群解散接口被调用! QQ群号码:'.$gcode, E_USER_WARNING );
	ini_set('display_errors', $de);

	$qg = new QQGroupPacket();
    $qg->addUInt($gcode);
	$qg->addUInt($noblack? 1: 0);
    if ( $qg->request(OIGW_GROUP_CMD_GAMEOVER) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 ) {
            //群不存在
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_notice_client_update() */
/**
 * 通知QQ客户端一个群的资料发生了变更
 *
 * @param   int				$suin				发出请求的QQ号
 * @param   int				$gcode				群外部号码
 * @param   [bool]			$immediately = true	是否立即更新
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_notice_client_update($suin, $gcode, $immediately = true)
{
	$qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUChar(1); //1 - 固定群   2-临时群
    $qg->addUChar(1); //ver
    $qg->addUInt($suin);

    $qg->addUShort(10);
    $qg->addUShort(OIGW_GROUP_CMD_SPECIAL_MSG_INFO);
    $qg->addUInt(0);
    $qg->addUInt($immediately? 0xffffffff: 0);

    if ( $qg->request(OIGW_GROUP_CMD_SPECIAL_MSG) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 ) {
            //群不存在
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_notice_client_face() */
/**
 * 通知QQ客户端一个群的成员头像发生了变更
 *
 * @param   int				$suin				发出请求的QQ号
 * @param   int				$gcode				群外部号码
 * @param   int				$uin				发生变更的QQ号
 * @param   [int]			$reqtime = time()	时间戳（默认为当前时间）
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_notice_client_face($suin, $gcode, $uin, $reqtime = 0)
{
	$qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUChar(1); //1 - 固定群   2-临时群
    $qg->addUChar(1); //ver
    $qg->addUInt($suin);

    $qg->addUShort(10);
    $qg->addUShort(OIGW_GROUP_CMD_SPECIAL_MSG_FACE);
    $qg->addUInt($uin);
    $qg->addUInt($reqtime==0? $_SERVER['REQUEST_TIME']: $reqtime);

    if ( $qg->request(OIGW_GROUP_CMD_SPECIAL_MSG) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 ) {
            //群不存在
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/* {{{ function qp_oigw_group_notice_client_tab() */
/**
 * 通知QQ客户端一个群的动态TAB页面发生了变更
 *
 * @param   int				$uin				发出请求的QQ号
 * @param   int				$gcode				群外部号码
 * @param   [int]			$reqtime = time()	时间戳（默认为当前时间）
 * @return  true: ok; false: fail.
 */
function qp_oigw_group_notice_client_tab($suin, $gcode, $reqtime = 0)
{
	$qg = new QQGroupPacket();
    $qg->addUInt($gcode);
    $qg->addUChar(1); //1 - 固定群   2-临时群
    $qg->addUChar(1); //ver
    $qg->addUInt($suin);

    $qg->addUShort(6);
    $qg->addUShort(OIGW_GROUP_CMD_SPECIAL_MSG_TAB);
    $qg->addUInt($reqtime==0? $_SERVER['REQUEST_TIME']: $reqtime);

    if ( $qg->request(OIGW_GROUP_CMD_SPECIAL_MSG) )
    {
        $result = $qg->getUChar();
        if ( $result == 0 )
        {
            return true;
        } else if ( $result == 2 ) {
            //群不存在
            return null;
        } else {
            //失败
            trigger_error( $qg->getReset(), E_USER_WARNING );
            return false;
        }
    }
}
/* }}} */


/**
 * QP SOCKET lib class.
 */
class qp_socket
{
    /**
     * @access  private
     * @var     resource    socket handle resource.
     */
    private $sock = 0;

    /**
     * @access  private
     * @var     string      Server host.
     */
    private $host = '';

    /**
     * @access  private
     * @var     int         Server port.
     */
    private $port = 0;

    /**
     * @access  private
     * @var     float       Time out.
     */
    private $timeout = 2;

    /**
     * @access  private
     * @var     string      Buffer string.
     */
    private $buffer = '';

    /**
     * @access  private
     * @var     string      End of line.
     */
    private $eol = "\r\n";


    /* {{{ function qp_socket( $host = '', $port = 0, $timeout = 2 ) */
    /**
     * Initialize Socket.
     *
     * @param   string  $host       Server host/IP.
     * @param   int     $port       Server port.
     * @param   float   $timeout    Time out.
     * @param   string  $eol        End of line.
     */
    function qp_socket( $host = '', $port = 0, $timeout = 2, $eol = "\r\n" )
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->timeout  = $timeout;
        $this->eol      = $eol;
        return(0);
    }
    /* }}} */


    /* {{{ function set_server( $host, $port, $timeout = 2 ) */
    /**
     * Set server.
     *
     * @param   string  $host       Server host/IP.
     * @param   int     $port       Server port.
     * @param   float   $timeout    Time out.
     */
    function set_server( $host, $port, $timeout = 2 )
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        return(0);
    }
    /* }}} */


    /* {{{ function set_eol( $eol = "\r\n" ) */
    /**
     * Set end of line.
     *
     * @param   string  $eol        End of line.
     */
    function set_eol( $eol = "\r\n" )
    {
        $this->eol = $eol;
        return(0);
    }
    /* }}} */


    /* {{{ function connect() */
    /**
     * Connect Socket.
     *
     * @return  int             0: ok, other: fail.
     */
    function connect()
    {
        $this->host = trim( $this->host );
        $this->sock = @fsockopen( $this->host, $this->port, $errno, $errstr, $this->timeout );

        if ( $this->sock > 0 ) {
            return(0);
        } else {
            return(-1);
        } // if
    }
    /* }}} */


    /* {{{ function close() */
    /**
     * Close socket.
     *
     * @return  int             0: ok, other: fail.
     */
    function close()
    {
        if ( ( $this->sock > 0 ) && ( TRUE == fclose($this->sock) ) ) {
            return(0);
        } else {
            return(-1);
        } // if
    }
    /* }}} */


    /* {{{ function read_line() */
    /**
     * Read a data line from socket. Note that fgets will return every
     * chars in a line, so this function may return "\r\n" or "\n"
     * at the end.
     *
     * @return  string          data string (FALSE: fail).
     */
    function read_line()
    {
        if ( $this->sock <= 0 ) {
            return(FALSE);
        } // if
        $this->buffer = fgets( $this->sock );
        return( $this->buffer );
    }
    /* }}} */


    /* {{{ function write_line( $str ) */
    /**
     * Write a data line to socket.
     *
     *  @param   string  $str        String to write.
     *  @result  int                 TRUE: ok, FALSE: fail.
     */
    function write_line( $str )
    {
        if ( $this->sock <= 0 ) {
            return(FALSE);
        } // if
        $ret = fputs( $this->sock, $str.$this->eol );
        return( $ret );
    }
    /* }}} */

}


/* {{{ function qp_parse_str( $str, $keysep = '&', $valsep = '=', $decode = TRUE ) */
/**
 * Parse the string.
 *
 * @param   string  $str    String for parse.
 * @param   string  $keysep Key separator.
 * @param   string  $valsep Value separator.
 * @param   int     $decode TRUE: need decode, FALSE: not need.
 * @return  array           The array of parsed result.
 */
function qp_parse_str( $str, $keysep = '&', $valsep = '=', $decode = TRUE )
{
    $ret = array();
    $info = explode( $keysep, $str );
    foreach ( $info as $key => $val )
	{
        $line = explode( $valsep, $val );
        if ( $decode && !empty($line[1]) )
		{
            $line[1] = urldecode( $line[1] );
        } // if
        $ret[ $line[0] ] = @$line[1];
    } // foreach
    return( $ret );
}
/* }}} */


/* {{{ function qp_build_str( $array, $keysep = '&', $valsep = '=', $encode = FALSE ) */
/**
 * Build string.
 *
 * @param   array   $array  Array to build.
 * @param   string  $keysep Key separator.
 * @param   string  $valsep Value separator.
 * @param   int     $encode TRUE: need url encode, FALSE: no need.
 * @return  string          The built string.
 */
function qp_build_str( $array, $keysep = '&', $valsep = '=', $encode = FALSE )
{
    $str = '';
	foreach ( $array as $key => $val )
	{
        if ( $encode )
		{
			$val = urlencode( $val );
		}
        $str .= ( $key.$valsep.$val.$keysep );
    } // foreach
    $str = substr( $str, 0, strlen($str)-1 );
    return( $str );
}
/* }}} */

//End of script
