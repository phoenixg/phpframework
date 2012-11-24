<?php
class nbMvcHelper
{
  public function getTemplatePathInfo($path)
  {
    if (strstr($path, '@'))
    {
      $path = nbRouter::getRouter(substr($path, 1));
    }

    $pathArray = preg_split('/\//', $path);

    if (isset($pathArray[2]))
    {
      $pathInfo['app'] = $pathArray[0];
      $pathInfo['module'] = $pathArray[1];
      $pathInfo['action'] = $pathArray[2];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else if (isset($pathArray[1]))
    {
      $pathInfo['app'] = nbAppHelper::getAppName();
      $pathInfo['module'] = $pathArray[0];
      $pathInfo['action'] = $pathArray[1];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else
    {
      $pathInfo['app'] = nbAppHelper::getAppName();
      $pathInfo['module'] = nbRequest::getInstance()->getModuleName();
      $pathInfo['action'] = $pathArray[0];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }

    return $pathInfo;
  }

  public function getLayoutPathInfo($path)
  {
    $pathArray = preg_split('/\//', $path);

    if (isset($pathArray[1]))
    {
      $pathInfo['app'] = $pathArray[0];
      $pathInfo['file'] = $pathArray[1];
      $pathInfo['appRoot'] = nbHelper::getAppRootByAppName($pathInfo['app']);
    }
    else
    {
      $pathInfo['app'] = nbAppHelper::getAppName();
      $pathInfo['file'] = $pathArray[0];
      $pathInfo['appRoot'] = nbHelper::getAppRootByAppName($pathInfo['app']);
    }

    return $pathInfo;
  }

  public static function redirect($path, $replace = false)
  {
    if ($replace)
    {
      echo "<script>window.location.href='$path';</script>";
    }
    else
    {
      echo "<script>window.location.href='$path';</script>";
    }
    exit;
  }

  public static function getActionPathInfo($path)
  {
    if (strstr($path, '@'))
    {
      $path = nbRouter::getRouter(substr($path, 1));
    }

    $pathArray = preg_split('/\//', $path);

    if (isset($pathArray[2]))
    {
      $pathInfo['app'] = $pathArray[0];
      $pathInfo['module'] = $pathArray[1];
      $pathInfo['action'] = $pathArray[2];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else if (isset($pathArray[1]))
    {
      $pathInfo['app'] = nbAppHelper::getAppName();
      $pathInfo['module'] = $pathArray[0];
      $pathInfo['action'] = $pathArray[1];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else
    {
      $pathInfo['app'] = nbAppHelper::getAppName();
      $pathInfo['module'] = nbRequest::getInstance()->getModuleName();
      $pathInfo['action'] = $pathArray[0];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }

    return $pathInfo;
  }

  public static function executeComponent($path, array $parameters = array())
  {
    $pathInfo = nbMvcHelper::getActionPathInfo($path);

    $componentPath = $pathInfo['appRoot']."modules".DIRECTORY_SEPARATOR.ucfirst($pathInfo['module']).".components.php";

    new nbLog("Execute component $componentPath");

    if (is_file($componentPath))
    {
      include_once $componentPath;
    }
    else
    {
      throw new nbAddonException('need action file at: ' . $componentPath);
    }

    $componentClassName = ucfirst($pathInfo['module'])."Components";
    if (class_exists($componentClassName))
    {
      $componentClass = new $componentClassName();
    }
    else
    {
      throw new nbAddonException('class ' . $componentClassName . ' don\'t exist in file ' . $componentPath);
    }

    if (method_exists($componentClass, $pathInfo['action'].'Action'))
    {
      $return = call_user_func(array($componentClass, $pathInfo['action'].'Action'), $parameters);
      if ($return)
      {
        nbData::set('return', $return, 'nb-mvc-return');
      }
    }
    else
    {
      throw new nbAddonException('method ' . $pathInfo['action'].'Action don\'t exist in class ' . $componentClassName);
    }

    return $componentClass;
  }

  public static function executeBlock($path, array $parameters = array())
  {
    $pathInfo = nbMvcHelper::getActionPathInfo($path);

    $blockPath = $pathInfo['appRoot']."modules".DIRECTORY_SEPARATOR.ucfirst($pathInfo['module']).".block.php";

    new nbLog("Execute block $blockPath");

    if (is_file($blockPath))
    {
      include_once $blockPath;
    }
    else
    {
      throw new nbAddonException('need block file at: ' . $blockPath);
    }

    $componentClassName = ucfirst($pathInfo['module'])."Components";
    if (class_exists($componentClassName))
    {
      $componentClass = new $componentClassName();
    }
    else
    {
      throw new nbAddonException('class ' . $componentClassName . ' don\'t exist in file ' . $componentPath);
    }

    if (method_exists($componentClass, $pathInfo['action'].'Action'))
    {
      $return = call_user_func(array($componentClass, $pathInfo['action'].'Action'), $parameters);
      if ($return)
      {
        nbData::set('return', $return, 'nb-mvc-return');
      }
    }
    else
    {
      throw new nbAddonException('method ' . $pathInfo['action'].'Action don\'t exist in class ' . $componentClassName);
    }

    return $componentClass;
  }

  public static function executePartial($path, array $parameters = array())
  {
    $pathInfo = nbMvcHelper::getTemplatePathInfo($path);

    $templatePath = $pathInfo['appRoot']."templates".DIRECTORY_SEPARATOR.$pathInfo['module'].DIRECTORY_SEPARATOR.$pathInfo['action'].".php";

    if (!file_exists($templatePath))
    {
      throw new nbAddonException($templatePath.' doesnot exist');
    }

    return nbMvcHelper::executeTemplate($templatePath, $parameters);
  }

  public static function executeTemplate($__templatePath, $vlaues)
  {
    new nbLog("Execute template $__templatePath", 'mvc-template');
    foreach ($vlaues as $__name => $__value)
    {
      $$__name = $__value;
    }

    if (file_exists($__templatePath))
    {
      ob_start();
      include($__templatePath);
      $contents = ob_get_contents();
      ob_end_clean();
    }
    else
    {
      throw new nbAddonException('template not exists: '.$__templatePath);
    }

    return $contents;
  }
}