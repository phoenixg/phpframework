<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>QQ User Login</title>
<script>
var serverType = '<?php echo $_SERVER['SERVER_TYPE'] ?>';
var currentPath = '<?php echo nbRequest::getInstance()->getHost() ?>qqact/';
var APPID = <?php echo $_SERVER['APPID'] ?>;
var tamsid = <?php echo nbHelper::getConfig('minisite/tamsId'); ?>;
</script>
<script type="text/javascript" src="/qqact/QQAct.js"></script>

</head>
<body>
<script type="text/javascript">
if (QQAct.IsLogin())
{
  window.location.href = '<?php echo str_replace('&amp;', '&', $url) ?>';
}
else
{
  function ptlogin2_onClose()
  {
    window.location.href = '<?php echo nbRequest::getInstance()->getHost() ?>';
  }
  QQAct.LoginQQ('<?php echo str_replace('&amp;', '&', $url) ?>');
}
</script>
</body>
</html>