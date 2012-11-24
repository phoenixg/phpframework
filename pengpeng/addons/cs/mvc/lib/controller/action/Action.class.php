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
class Action
{
  private $appLayout = '';
  private $globalLayout = '';
  private $template = '';

  public $contents = '';

  /**
   *
   * @param array $view
   * @return void
   */
  public function __construct($view = array())
  {
    if ($view)
    {
      $response = Response::getInstance();
      //      $response->useJs((array)$view['js']);
      //      $response->useCss((array)$view['css']);
      //      $response->useTitle($view['title']);
      $response->useMeta((array)$view['meta']);
      //      $this->setLayout($view['layout']);
    }
  }

  /**
   *
   * @param string $moduleName
   * @param string $actionName
   * @return void
   */
  public function forward($moduleName, $actionName)
  {
    nbRequest::getInstance()->setModuleName($moduleName);
    nbRequest::getInstance()->setActionName($actionName);
    FilterChain::getInstance()->startFilterChain();
    exit;
  }

  /**
   *
   * @param string $path
   * @return void
   */
  public function setAppLayout($path)
  {
    $this->appLayout = $path;
  }

  /**
   *
   * @return string
   */
  public function getAppLayout()
  {
    return $this->appLayout;
  }

  public function setGlobalLayout($path)
  {
    $this->appLayout = $path;
  }

  /**
   *
   * @return string
   */
  public function getGlobalLayout()
  {
    return $this->appLayout;
  }


  /**
   *
   * @param string $path
   * @return void
   */
  protected function setTemplate($path, $type = 'Action')
  {
    $this->template = $path;
  }

  /**
   *
   * @return string
   */
  public function getTemplate()
  {
    return $this->template;
  }

  public function setContents($contents)
  {
    $this->contents = $contents;
  }

  /**
   *
   * @param string $path
   * @return void
   */
  public function redirect($path, $query = '')
  {
    $url = HtmlHelper::url($path, $query);
    echo "<script>window.location.href='$url'</script>";
    exit;
  }
}