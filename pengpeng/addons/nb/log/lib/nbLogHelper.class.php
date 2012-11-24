<?php
class nbLogHelper
{
  public static function getDay($traceInfo)
  {
    return date('Y-m-d');
  }

  public static function getTime($traceInfo)
  {
    list($microtime, $time) = explode(' ', microtime());
    return $time;
  }

  public static function getMicrotime($traceInfo)
  {
    list($microtime, $time) = explode(' ', microtime());
    return $microtime;
  }

  public static function getApp($traceInfo)
  {
    $file = $traceInfo[0][1]['file'];

    return nbAppHelper::getAppNameByFilePath($file);
  }

  public static function getMessage($traceInfo)
  {
    return $traceInfo[0][1]['args'][0];
  }

  public static function getFile($traceInfo)
  {
    return $traceInfo[0][1]['file'];
  }

  public static function getLine($traceInfo)
  {
    return $traceInfo[0][1]['line'];
  }

  public static function logAble()
  {
    if (in_array(nbToolHelper::getIp(), nbAppHelper::getCurrentAppConfig('alwaysOpenForIp', __FILE__)))
    {
      return true;
    }

    if (nbAppHelper::getCurrentAppConfig('enable', __FILE__))
    {
      return true;
    }

    return false;
  }
}