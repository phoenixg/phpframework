<?php
/**
 * Copyright (c) 2009 3Guys, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF 3Guys, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

/**
 *
 * @author 3Guys
 *
 */
class nbRequest
{
  private static $instance;

  /**
   *
   * @param bool $newInstance
   * @return CsRequest
   */
  public static function getInstance($newInstance = false)
  {
    if ($newInstance || self::$instance == null)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private $moduleName;
  private $actionName;

  /**
   *
   * @param string $name
   * @param string $defaultValue
   * @return string
   */
  public function getGet($name, $defaultValue = '', $validateStrategy = 'haveValue')
  {
      //$_GET = get_magic_quotes_gpc() ? $this->stripslashesDeep($_GET) : $_GET;

    foreach ($_GET as $key => $parameter)
    {
      //$_GET[$key] = TMUtil::filterTextDeep($parameter);
    }

    if (!isset($_GET[$name]))
    {
      $_GET[$name] = '';
    }

    if (nbValidate::dealStrategy($_GET[$name], $validateStrategy))
    {
      return $_GET[$name];
    }
    else
    {
      return $defaultValue;
    }
  }

  /**
   *
   * @param string $name
   * @param string $defaultValue
   * @return string
   */
  public function getPost($name, $defaultValue = '')
  {
    //$_POST = get_magic_quotes_gpc() ? $this->stripslashesDeep($_POST) : $_POST;

    foreach ($_POST as $key => $parameter)
    {
      //$_POST[$key] = TMUtil::filterTextDeep($parameter);
    }

    if (isset($_POST[$name]))
    {
      $v = $_POST[$name];
    }
    else
    {
      $v = $defaultValue;
    }

    return $v;
  }

  /**
   *
   * @return string
   */
  public function getModuleName()
  {
    if (!$this->moduleName)
    {
      $urlWithoutApp = preg_replace('/^\/?[\w-]+\.php/', '', $_SERVER['REQUEST_URI']);

      preg_match('/^\/(.*?(?=\/|$|\?))/', $urlWithoutApp, $matchs);

      if (isset($matchs[1]) && $matchs[1])
      {
        $this->moduleName = $matchs[1];
      }
      else
      {
        $this->moduleName = nbAppHelper::getCurrentAppConfig('defaultModule', __FILE__);
      }
    }

    return $this->moduleName;
  }

  /**
   *
   * @param string $moduleName
   * @return void
   */
  public function setModuleName($moduleName)
  {
    $this->moduleName = $moduleName;
  }

  /**
   *
   * @return unknown_type
   */
  public function getActionName()
  {
    if (!$this->actionName)
    {
      preg_match('/^\/?([\w-]+\.php)?\/?(\w+(?=\/|$))\/?(\w+(?=\/|$|\?))/', $_SERVER['REQUEST_URI'], $matchs);

      if (isset($matchs[3]))
      {
        $this->actionName = $matchs[3];
      }
      else
      {
        $this->actionName = nbAppHelper::getCurrentAppConfig('defaultAction', __FILE__);
      }
    }

    return $this->actionName;
  }

  /**
   *
   * @param string $actionName
   * @return string
   */
  public function setActionName($actionName)
  {
    $this->actionName = $actionName;
  }

  /**
   *
   * @return unknown_type
   */
  public function isPost()
  {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  /**
   *
   * @return string
   */
  public function getHost()
  {
    if ($_SERVER['SERVER_PORT'] != 80)
    {
      $port = ':'.$_SERVER['SERVER_PORT'];
    }
    else
    {
      $port = '';
    }

    return "http://{$_SERVER['HTTP_HOST']}$port/";
  }


  public function getUrl()
  {
    return $this->getHost().substr($_SERVER['REQUEST_URI'], 1);
  }

  public function getUrlPath()
  {
    return $this->getHost().nbAppHelper::getAppName().'.php/'.$this->getModuleName().'/'.$this->getActionName().'/';
  }


  /**
   * Returns true if the request is a XMLHttpRequest.
   *
   * It works if your JavaScript library set an X-Requested-With HTTP header.
   * Works with Prototype, Mootools, jQuery, and perhaps others.
   *
   * @return bool true if the request is an XMLHttpRequest, false otherwise
   */
  public function isAjax()
  {
    return ($this->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
  }

  /**
   *
   * @param string $name
   * @param string $prefix
   * @return string|void
   */
  public function getHttpHeader($name, $prefix = 'http')
  {
    if ($prefix)
    {
      $prefix = strtoupper($prefix).'_';
    }

    $name = $prefix.strtoupper(strtr($name, '-', '_'));

    return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
  }

  public function getCookie($name, $defaultValue = null)
  {
    $retval = $defaultValue;

    if (isset($_COOKIE[$name]))
    {
      $retval = $_COOKIE[$name];
    }

    return $retval;
  }
}