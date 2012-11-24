<?php
class nbHelper
{
  public static function getAllConfig()
  {
    return nbConfigHelper::getAllConfig();
  }

  public static function getAppRootByAppName($appName)
  {
    $appRoots = nbHelper::getConfig('nb/appRoots');
    return $appRoots[$appName].DIRECTORY_SEPARATOR;
  }

  public static function currentInAction($actionsConfig)
  {
    $appName = nbAppHelper::getAppName();
    $moduleName = nbRequest::getInstance()->getModuleName();
    $actionName = nbRequest::getInstance()->getActionName();

    if ($actionsConfig == '*')
    {
      return true;
    }

    if (!key_exists($appName, $actionsConfig))
    {
      return false;
    }

    if ($actionsConfig[$appName] == '*')
    {
      return true;
    }

    if (!key_exists($moduleName, $actionsConfig[$appName]))
    {
      return false;
    }

    if ($actionsConfig[$appName][$moduleName] == '*')
    {
      return true;
    }

    if (in_array($actionName, $actionsConfig[$appName][$moduleName]))
    {
      return true;
    }

    return false;
  }

  public static function getAppRoot($appName)
  {
    $appRoots = nbHelper::getConfig('nb/appRoots');

    if (!isset($appRoots[$appName]))
    {
      if (SERVER_LEVEL < 75)
      {
        nbAutoconfigHelper::buildConfigCache();
        nbConfigHelper::loadAllConfigFile();

        $appRoots = nbHelper::getConfig('nb/appRoots');
        if (isset($appRoots[$appName]))
        {
          return $appRoots[$appName].DIRECTORY_SEPARATOR;
        }
      }

      throw new nbCoreException('do not have a app called '.$appName);
    }

    return $appRoots[$appName].DIRECTORY_SEPARATOR;
  }

  public static function getConfig($name)
  {
    return nbConfigHelper::getConfig($name);
  }

  public static function loadConfig($configFile, $appName = '')
  {
    nbConfigHelper::loadConfigFile($configFile, $appName);
  }

  public static function useJs($value, array $options = array())
  {
    return nbHeadHelper::useJs($name, $options);
  }

  public static function useCss($value, array $options = array())
  {
    return nbHeadHelper::useCss($name, $options);
  }

  public static function useMeta($value, array $options = array())
  {
    return nbHeadHelper::useMeta($name, $options);
  }

  public static function useTitle($value)
  {
    return nbHeadHelper::setTitle($value);
  }

  public static function loadApp($appPath, $para = array())
  {
    nbAppHelper::loadApp($appPath, $para);
  }
}