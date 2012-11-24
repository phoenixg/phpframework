<?php
include(FRAMEWORK_ROOT.'autoconfig/config/config.php');
$cacheFile = $config['saveFile'];

$generate = false;
$needReload = false;

if (!is_file($cacheFile))
{
  $needReload = true;
}
else
{
  nbConfigHelper::loadAllConfigFile();

  if (SERVER_LEVEL < 75)
  {
    $appRoots = nbConfigHelper::getConfig('nb/appRoots');
    // when you create a new app and visit it
    if (!isset($appRoots[nbAppHelper::getAppName()]))
    {
      $needReload = true;
    }
    else
    {
      foreach (nbConfigHelper::getConfig('nb/appConfigTimes') as $appName => $appConfigTime)
      {
        $appConfigFile = $appRoots[$appName].DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
        if (!is_file($appConfigFile))
        {
          $needReload = true;
          break;
        }
        else if ($appConfigTime < filemtime($appConfigFile))
        {
          $needReload = true;
          break;
        }
      }
    }
  }
}

if ($needReload)
{
  nbAutoconfigHelper::buildConfigCache();
  nbConfigHelper::loadAllConfigFile();
  new nbLog('generate autoconfig');
}