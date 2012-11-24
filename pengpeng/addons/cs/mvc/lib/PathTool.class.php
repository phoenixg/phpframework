<?php
class PathTool
{
  public static function readPath($path, $defaultModule = APP, $defaultAction = null)
  {
    return self::readThreePartPath($path, $defaultModule, $defaultAction);
  }

  public static function readTwoPartPath($path, $defaultApp = APP)
  {
    $pathArray = preg_split('/\//', $path);

    if (isset($pathArray[1]))
    {
      $pathInfo['app'] = $pathArray[0];
      $pathInfo['file'] = $pathArray[1];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else
    {
      $pathInfo['app'] = $defaultApp;
      $pathInfo['file'] = $pathArray[0];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }

    return $pathInfo;
  }

  public static function readThreePartPath($path, $defaultModule = APP, $defaultAction = null)
  {
    //$caller = Request::getInstance()->getGet('caller');
    //    if ($caller)
    //    {
    //      $callerArray = preg_split('/\//', $caller);
    //    }

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
      $pathInfo['app'] = isset($callerArray[0]) ? $callerArray[0] : $defaultModule;
      $pathInfo['module'] = $pathArray[0];
      $pathInfo['action'] = $pathArray[1];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }
    else
    {
      if (!$defaultAction)
      {
        $defaultAction = Request::getInstance()->getModuleName();
      }

      $pathInfo['app'] = isset($callerArray[0]) ? $callerArray[0] : $defaultModule;
      $pathInfo['module'] = isset($callerArray[1]) ? $callerArray[1] : $defaultAction;
      $pathInfo['action'] = $pathArray[0];
      $pathInfo['appRoot'] = nbHelper::getAppRoot($pathInfo['app']);
    }

    return $pathInfo;
  }

  public static function getTemplateFileByPath($path, $type)
  {
    if (!in_array($type, array('action', 'component', 'slot', 'partial')))
    {
      throw new Exception('Parameter must be action, component, slot, partial');
    }

    $pathInfo = self::readPath($path);

    if ($pathInfo['module'] == 'global')
    {
      $modulePath = '';
    }
    else
    {
      $modulePath = $pathInfo['module'].'/';
    }

    if ($type != 'partial' && $type != 'action')
    {
      $templatePath = $pathInfo['appRoot']."templates/$modulePath{$pathInfo['action']}$type.php";
    }
    else
    {
      $templatePath = $pathInfo['appRoot']."templates/$modulePath{$pathInfo['action']}.php";
    }

    return $templatePath;
  }

  public static function getActionFileByPath($path, $type)
  {
    if (!in_array($type, array('actions', 'action', 'component', 'slot')))
    {
      throw new Exception('Parameter must be actions, action, component, slot');
    }

    $pathInfo = self::readPath($path);

    if ($type == 'actions')
    {
      $actionPath = ConvertTool::toPascal($pathInfo['module']).'.actions';
    }
    else
    {
      $actionPath = ConvertTool::toPascal($pathInfo['action']).'.'.$type;
    }

    return $pathInfo['appRoot']."modules/{$pathInfo['module']}/actions/$actionPath.php";
  }

  public static function getLayoutFileByPath($path)
  {
    $pathInfo = self::readTwoPartPath($path);
    return $pathInfo['appRoot'].'layout/'.$pathInfo['file'].'.php';
  }
}