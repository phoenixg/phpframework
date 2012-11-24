<?php
class TaeConstants{
	//前缀常量
	const HEADER_PREFIX= "header.";
	const BODY_PREFIX = "body.";
	//配置项常量
	const UIN = "uin";
	const USER_IP = "usr_ip";
	const ACT_ID = "act_id";
	//const CONF_FILE = "conf_file";
	const VERSION = "version";
	const SERVER_IP = "server_ip";
	const SERVER_PORT = "server_port";
	//命令号
	const CMD_GET_FRIEND_LIST = 1001;
	const CMD_GET_NICK = 1004;
	const CMD_GET_FRIEND_GROUP = 1000;
	const CMD_SEND_ITEM = 1100;
	const CMD_QZONE_BLOG = 1201;
	const CMD_QZONE_PENDANT = 1202;
	const CMD_QZONE_VOTE = 1204;
	//错误码
	const ERR_FILE_NOT_FOUND = -1;
	const ERR_NOT_INITED = -2;
	const ERR_DECODE_FAIL = -3;
	//exception code
	const EXCEPTION_TAE = 22;
	
	public static function getCommandServer($cmd)
	{
		if($cmd>=1000&&$cmd<=1099)
		{
			return "oidbproxy";
		}else if($cmd>=1100&&$cmd<=1199)
		{
			return "mpproxy";
		}else if($cmd>=1200&&$cmd<=1299)
		{
			return "b2proxy";
		}else{
			return "";
		}
	}
}
?>