<?php
class MvcHtmlHelper
{
  public static function linkTo($title, $path, $parameters = array())
  {
    if (isset($parameters['query']))
    {
      $query = $parameters['query'];
      unset($parameters['query']);
    }
    else
    {
      $query = '';
    }

    $url = self::url($path, $query);

    return TagHelper::tag('a', $parameters + array('href' => $url), $title);
  }

  public static function url($path, $query = '')
  {
    if (preg_match('/^https?:\/\//', $path))
    {
      $url = $path;
    }
    else
    {
      $pathInfo = PathTool::readPath($path);

      if ($pathInfo['app'] == ConfigTool::getAddonConfig('mvc/defaultApp'))
      {
        $url = Request::getInstance()->getHost().$pathInfo['module'].'/'.$pathInfo['action'];
      }
      else
      {
        $url = Request::getInstance()->getHost().$pathInfo['app'].'.php/'.$pathInfo['module'].'/'.$pathInfo['action'];
      }
    }

    if ($query)
    {
      $url .= '?'.$query;
    }

    return $url;
  }

  public static function includePartial($path, array $parameters = array())
  {
    echo self::getPartial($path, $parameters);
  }

  public static function getPartial($path, array $parameters = array())
  {
    $pathInfo = PathTool::readPath($path);
    AppTracter::getInstance()->addAppTracter($pathInfo['app']);

    $templatePath = PathTool::getTemplateFileByPath($path, 'Partial');

    if (!file_exists($templatePath))
    {
      throw new HelperException($templatePath.' doesnot exist');
    }

    AppTracter::getInstance()->addAppTracter(AppTracter::getInstance()->getStartApp());

    return executeTemplate($templatePath, $parameters);
  }

  public static function includeComponent($path, array $parameters = array())
  {
    echo self::getComponent($path, $parameters);
  }

  /**
   * 在Action中指定template具有最高的优先级，然后是通过第三个参数传递过来的template，其次是通过action自己解析出来的
   *
   * @param $path
   * @param array $parameters
   * @param $templatePath
   * @return unknown_type
   */
  public static function getComponent($path, array $parameters = array(), $templatePath = null)
  {
    $actionClass = ActionTool::executeForAction($path, 'Component', $parameters);

    if (!$actionClass)
    {
      $actionPathSingle = PathTool::getActionFileByPath($path, 'Component');
      $actionPathTogether = PathTool::getActionFileByPath($path, 'Actions');
      throw new MvcException ("$actionPathSingle or $actionPathTogether doesn't exist");
    }

    if ($actionClass->getTemplate())
    {
      //echo 3;
      $template = PathTool::getTemplateFileByPath($actionClass->getTemplate(), 'Component');;
    }
    else if ($templatePath)
    {
      $template = PathTool::getTemplateFileByPath($templatePath, 'Component');
    }
    else
    {
      $template = PathTool::getTemplateFileByPath($path, 'Component');
    }

    AppTracter::getInstance()->addAppTracter(AppTracter::getInstance()->getStartApp());

    //在component机制中，传给Action中的变量，也会在template中存在
    foreach ($parameters as $name => $value)
    {
      if (!isset($actionClass->$name) || !$actionClass->$name)
      {
        $actionClass->$name = $value;
      }
    }

    return executeTemplate($template, $actionClass);
  }

  public static function truncate($text, $length = 30, $truncateString = '...', $truncateLastspace = false)
  {
    if ($text == '')
    {
      return '';
    }

    $text = strip_tags($text);

    $mbstring = extension_loaded('mbstring');
    if ($mbstring)
    {
      @mb_internal_encoding(mb_detect_encoding($text));
    }
    $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
    $substr = ($mbstring) ? 'mb_substr' : 'substr';

    if ($strlen($text) > $length)
    {
      $truncateText = $substr($text, 0, $length - $strlen($truncateString));
      if ($truncateLastspace)
      {
        $truncateText = preg_replace('/\s+?(\S+)?$/', '', $truncateText);
      }

      return $truncateText.$truncateString;
    }
    else
    {
      return $text;
    }
  }

  /**
   * $path like 'app/module/action';
   *
   * @param string $slotName
   * @param string $path
   * @return unknown_type
   */
  public static function includeSlot($slotName, $path)
  {
    echo self::getSlot($slotName, $path);
  }

  public static function getSlot($slotName, $path)
  {
    $pathInfo = PathTool::readPath($slotName);
    AppTracter::getInstance()->addAppTracter($pathInfo['app']);

    $moduleName = $pathInfo['module'];
    $actionName = $pathInfo['action'];

    $request = Request::getInstance();

    $actionPathSingle = PathTool::getActionFileByPath($slotName, 'Slot');
    $actionPathTogether = PathTool::getActionFileByPath($slotName, 'Actions');

    $actionClass = ActionTool::executeForAction($slotName, 'Slot');
    if (!$actionClass)
    {
      $actionClass = ActionTool::executeForAction($path, 'Slot');
      if (!$actionClass)
      {
        throw new MvcException ("can't execute slot at $slotName or $path");
      }
    }

    if ($actionClass->getTemplatePath())
    {
      //echo 3;
      $templatePath = $actionClass->getTemplatePath();
    }
    else
    {
      //echo 4;
      $templatePath = PathTool::getTemplateFileByPath($path, 'Slot');
    }

    AppTracter::getInstance()->addAppTracter(AppTracter::getInstance()->getStartApp());

    return executeTemplate($templatePath, $actionClass);
  }
}