<?php
class nbConfigHelper
{
  public static function getConfig($name)
  {
    $configAlias = nbData::get('nb/configAlias', 'nb-config');
    if (isset($configAlias[$name]))
    {
      $name = $configAlias[$name];
    }

    if (nbData::has($name, 'nb-config'))
    {
      return nbData::get($name, 'nb-config');
    }
    else
    {
      $autoconfig = new nbAutoconfig();
      $autoconfig->execute();

      new nbLog("generate autoconfig for app: $name");

      nbHelper::loadConfig(self::getConfig('nb-autoconfig/saveFile'));

      if (nbData::has($name, 'nb-config'))
      {
        return nbData::get($name, 'nb-config');
      }
      else
      {
        new nbLog("cant find a addon config called '$name'");
        //throw new nbCoreException("cant find a addon config called '$name'");
      }
    }
  }

  public static function loadConfigFile($configFile, $appName = '')
  {
    $config = self::getConfigFile($configFile);

    foreach (isset($config) ? $config : array() as $configName => $value)
    {
      if (strstr('/', $configName) && $appName)
      {
        $configName = $appName.'/'.$configName;
      }
      nbData::set($configName, $value, 'nb-config');
    }
  }

  public static function getConfigFile($configFile)
  {
    include $configFile;

    if (!isset($config))
    {
      $config = array();
    }

    return $config;
  }

  public static function setConfig($name, $value)
  {
    nbData::set($name, $value, 'nb-config');
  }

  public static function getAllConfig()
  {
    return nbData::getAll('nb-config');
  }

  public static function loadAllConfigFile()
  {
    include(FRAMEWORK_ROOT.'autoconfig/config/config.php');
    $cacheFile = $config['saveFile'];

    // load config from cache: /cache/config/config.php
    nbConfigHelper::loadConfigFile($cacheFile);

    // load global config file: /config/config.php
    $file = nbConfigHelper::getConfig('nb-autoconfig/globalConfigFile');
    if (is_file($file))
    {
      nbConfigHelper::loadConfigFile(nbConfigHelper::getConfig('nb-autoconfig/globalConfigFile'));
    }

    // load app config file: /apps/default/config/config.php
    $appName = nbAppHelper::getAppName();
    $appConfigFile = nbAppHelper::getAppRoot() . 'config'. DIRECTORY_SEPARATOR . $appName . '.config.php';

    if (is_file($appConfigFile))
    {
      nbHelper::loadConfig($appConfigFile, $appName);
    }

    // load parent app config file (tm-component-vote for example):
    // 1./config/tm.config.php
    // 2./config/tm-component.config.php
    // 3./config/tm-component-vote.config.php
    $baseName = '';
    foreach (explode('-', $appName) as $subName)
    {
      $baseName .= $baseName ? '-'.$subName : $subName;

      $appConfigFile = PROJECT_ROOT . 'config'. DIRECTORY_SEPARATOR . $baseName . '.config.php';

      if (is_file($appConfigFile))
      {
        nbHelper::loadConfig($appConfigFile, $appName);
      }
    }
  }
}