<?php
class CoreTool
{
  public static function getAppRoot($appName)
  {
    $appName = str_replace('-', '/', $appName);

    if (in_array($appName, nbHelper::getConfig('nb/appNames')))
    {
      return PROJECT_ROOT.'apps/'.$appName.'/';
    }
    else
    {
      return ADDONS_ROOT.$appName.'/';
    }
  }
}