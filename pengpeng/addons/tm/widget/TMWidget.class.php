<?php
class TMWidget
{
  public static function displayToolbar()
  {
    $tamsId = nbHelper::getConfig('minisite/tamsId');
    $appId = $_SERVER['APPID'];
    $baseurl = nbRequest::getInstance()->getHost();
    $inviteUrl = TMInviteTool::linkToInvite(false);
echo <<<toolbar
<script>
appConfig = {};
appConfig.baseurl = '$baseurl';
appConfig.inviteUrl = '$inviteUrl';
appConfig.appid = '$appId';
</script>
<link rel="stylesheet" type="text/css" href="http://toolbar.tae.qq.com/2.2/css/default.css" />
<script type="text/javascript" src="http://toolbar.tae.qq.com/?v=2.2&f=QQAct&a=$appId&t=$tamsId&r=$inviteUrl&k=" charset="utf-8"></script>

toolbar;
  }
}

?>
