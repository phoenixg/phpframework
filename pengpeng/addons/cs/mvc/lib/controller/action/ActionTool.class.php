<?php
class ActionTool
{
  /**
   *
   * @param $actionPathSingle
   * @param $actionPathTogether
   * @return Action | bool
   */
  public static function executeForAction($path, $type, $parameters = array())
  {
    $pathinfo = PathTool::readPath($path);

    $actionPathSingle = PathTool::getActionFileByPath($path, $type);
    $actionPathTogether = PathTool::getActionFileByPath($path, 'actions');

    if (file_exists($actionPathSingle))
    {
      include_once $actionPathSingle;
      $actionClassName = ConvertTool::toPascal($pathinfo['action']).$type;
      $actionClass = new $actionClassName();
      call_user_func(array($actionClass, 'execute'), $parameters);
      new nbLog("Execute action at $actionPathSingle", 'cs-mvc-action');

      return $actionClass;
    }
    else if (file_exists($actionPathTogether))
    {
      include_once $actionPathTogether;
      $actionClassName = ConvertTool::toPascal($pathinfo['module'])."Actions";
      $actionClass = new $actionClassName();

      $preActionRules = nbHelper::getConfig('cs-mvc/preActionRules');

      foreach ($preActionRules as $preRule => $preActionName)
      {
        if (preg_match($preRule, $pathinfo['action']))
        {
          call_user_func(array($actionClass, $preActionName), $parameters);
        }
      }

      call_user_func(array($actionClass, ConvertTool::toCamel($pathinfo['action']).ConvertTool::toPascal($type)), $parameters);

      $postActionRules = nbHelper::getConfig('cs-mvc/postActionRules');

      foreach ($postActionRules as $postRule => $postActionName)
      {
        if (preg_match($postRule, $pathinfo['action']))
        {
          call_user_func(array($actionClass, $postActionName), $parameters);
        }
      }
      $log = new nbLog("Execute action at $actionPathTogether", 'cs-mvc-action');
      //$log->log();

      return $actionClass;
    }
    else
    {
      new nbLog("Execute action at $actionPathSingle or $actionPathTogether", 'cs-mvc-action');

      return false;
    }
  }
}