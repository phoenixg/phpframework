<?php
class nbMvcWidget
{
  public static function includePartial($path, array $parameters = array())
  {
    echo self::getPartial($path, $parameters);
  }

  public static function getPartial($path, array $parameters = array())
  {
    return nbMvcHelper::executePartial($path, $parameters);
  }

  public static function includeComponent($path, array $parameters = array())
  {
    echo self::getComponent($path, $parameters);
  }

  public static function getComponent($path, array $parameters = array())
  {
    $componentClass = nbMvcHelper::executeComponent($path, $parameters);

    $returnValue = nbData::get('return', 'nb-mvc-return');
    if ($returnValue)
    {
      nbData::clear('nb-mvc-return');
      return $returnValue;
    }

    if (!$componentClass->getTemplate())
    {
      $pathInfo = nbMvcHelper::getTemplatePathInfo($path);
      $templatePath = $pathInfo['appRoot']."templates".DIRECTORY_SEPARATOR.$pathInfo['module'].DIRECTORY_SEPARATOR.$pathInfo['action'].".php";
    }
    else
    {
      $template = $componentClass->getTemplate();
      $pathInfo = nbMvcHelper::getTemplatePathInfo($template);
      $templatePath = $pathInfo['appRoot']."templates".DIRECTORY_SEPARATOR.$pathInfo['module'].DIRECTORY_SEPARATOR.$pathInfo['action'].".php";
    }

    return nbMvcHelper::executeTemplate($templatePath, $componentClass);
  }

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

    if (strstr($path, 'javascript:'))
    {
      $url = $path;
    }
    else
    {
      $url = self::url($path, $query);
    }

    return nbHtmlWidget::tag('a', $parameters + array('href' => $url), $title);
  }

  public static function url($path, $query = '')
  {
    if (preg_match('/^https?:\/\//', $path))
    {
      $url = $path;
    }
    else
    {
      $pathInfo = nbMvcHelper::getActionPathInfo($path);

      if ($pathInfo['app'] == nbHelper::getConfig('nb/defaultApp'))
      {
        $url = nbRequest::getInstance()->getHost().$pathInfo['module'].'/'.$pathInfo['action'];
      }
      else
      {
        $url = nbRequest::getInstance()->getHost().$pathInfo['app'].'.php/'.$pathInfo['module'].'/'.$pathInfo['action'];
      }
    }

    if (is_array($query))
    {
      $url.= '?'.http_build_query($query);
    }
    else if ($query)
    {
      $url .= '?'.$query;
    }

    return $url;
  }

  public static function truncate($value, $length)
  {
    return $value;
  }
}