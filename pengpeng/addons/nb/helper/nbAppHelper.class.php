<?php
class nbAppHelper
{
  private static $appName;
  private static $appRoot;

  public static function getAppName()
  {
    if (self::$appName === null)
    {
      if (preg_match('/^\/?([\w-]+)\.php/', $_SERVER['REQUEST_URI'], $matchs))
      {
        self::$appName = $matchs[1];
      }
      else
      {
        self::$appName = nbConfigHelper::getConfig('nb/defaultApp');
      }
    }

    return self::$appName;
  }

  public static function getAppRoot($appName = '')
  {
    if ($appName)
    {
      $appRoots = nbConfigHelper::getConfig('nb/appRoots');
      return $appRoots[$appName].DIRECTORY_SEPARATOR;
    }
    else
    {
      if (self::$appRoot === null)
      {
        $appRoots = nbConfigHelper::getConfig('nb/appRoots');
        self::$appRoot = $appRoots[nbAppHelper::getAppName()].DIRECTORY_SEPARATOR;
      }

      return self::$appRoot;
    }
  }

  public static function getAppNotePath($appName = '', $separator = DIRECTORY_SEPARATOR)
  {
    if (!$appName)
    {
      $appName = self::getAppName();
    }
    return str_replace('-', $separator, $appName).$separator;
  }

  public static function getAppNoteRoot($appName = '')
  {
    if (!$appName)
    {
      $appName = self::getAppName();
    }

    $appRoot = self::getAppRoot($appName);

    return str_replace(self::getAppNotePath($appName), '', $appRoot);
  }

  public static function setAppName($appName)
  {
    self::$appName = $appName;

    $appRoots = nbConfigHelper::getConfig('nb/appRoots');
    self::$appRoot = $appRoots[$appName].DIRECTORY_SEPARATOR;
  }

  public static function getAppNameByFilePath($filePath)
  {
    foreach (nbConfigHelper::getConfig('nb/appRoots') as $appName => $appRoot)
    {
      if (strstr($filePath, $appRoot))
      {
        return $appName;
      }
    }

    return false;
  }

  public static function getCurrentAppConfig($name, $file)
  {
    $appName = self::getAppNameByFilePath($file);
    return nbConfigHelper::getConfig($appName.'/'.$name);
  }

  public static function loadApp($appPath, $para = array())
  {
    if (!strstr($appPath, '/'))
    {
      $appPath = 'addons/'.$appPath;
    }

    list($type, $appName) = explode('/', $appPath);

    if ($type == 'addons')
    {
      $path = ADDONS_ROOT;
    }
    else if ($type == 'framework')
    {
      $path = FRAMEWORK_ROOT;
    }
    else if ($type == 'app')
    {
      $path = nbAppHelper::getAppRoot();
    }
    else
    {
      $path = nbAppHelper::getAppRoot();
    }

    $__filePath = $path.str_replace('-', '/', $appName).DIRECTORY_SEPARATOR."load.php";
    //echo $__filePath."<br />";
    if (is_file($__filePath))
    {
      extract($para);
      require($__filePath);
    }
    else
    {
      throw new nbCoreException('Need file '.$__filePath);
    }
  }

  public static function loadFrameworkApp($appPath, $para = array())
  {
    $appPath = 'framework/'.$appPath;
    self::loadApp($appPath, $para);
  }
}