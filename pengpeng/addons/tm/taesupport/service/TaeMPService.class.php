<?php
require_once '../core/TaeCore.class.php';

class TaeMPService{
	public static function sendItem($qq,$actId,$itemId,$count=1)
	{
		$para = array("dstuin"=>$qq,"ruleid"=>$actId,"presentid"=>$itemId,"presenttime"=>$count);
		return TaeCore::taeCall(TaeConstants::CMD_SEND_ITEM,$para);
	}
}
?>