<?php
class TaeIMService {
	/**
	 * 获取用户好友列表。只能获取当前请求QQ号码的好友关系链。
	 * 默认从cookie中读取skey。也可传入参数指定skey。
	 *
	 * @param string $skey
	 * @return array
	 */
	public static function getFriendList($skey = null)
	{
		if(empty($skey))
		{
			$skey = $_COOKIE['skey'];
		}
		$para = array("skey"=> $skey);
		return TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_LIST,$para);
	}
	
	public static function getNick($qq)
	{
		if(is_array($qq))
		{
			$list = '';
			foreach ($qq as $one)
			{
				$list.=$one.";";
			}
			$qq = substr($list,0,strlen($list)-1);
		}
		$para = array("uinlist"=>$qq,"skey"=>"abc");
		return TaeCore::taeCall(TaeConstants::CMD_GET_NICK,$para);
	}
	
	public static function getFriendGroup($skey = null)
	{
		if(empty($skey))
		{
			$skey = $_COOKIE['skey'];
		}
		$para = array("skey"=> $skey);
		return TaeCore::taeCall(TaeConstants::CMD_GET_FRIEND_GROUP,$para);
	}
}