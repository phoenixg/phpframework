<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbAction
{
  public $request;
  public $response;
  public $service;

  protected $layout = '';
  protected $template = '';

  public function __construct()
  {
    $this->request = nbRequest::getInstance();
    $this->response = nbResponse::getInstance();
    $this->service = new nbService;
  }

  public function alert($message, $path)
  {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <script>alert('$message');window.location.href='$path'</script>";
    exit;
  }

  public function redirect($path)
  {
    nbMvcHelper::redirect($path);
  }

  public function reflash()
  {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <script>window.location.reload()</script>";
    exit;
  }

  public function returnJson(array $message)
  {
    echo json_encode($message);
    exit;
  }

  public function setLayout($layout)
  {
    $this->layout = $layout;
  }

  public function getLayout()
  {
    return $this->layout;
  }

  public function setTemplate($template)
  {
    $this->template = $template;
  }

  public function getTemplate()
  {
    return $this->template;
  }

  public function forward($path)
  {
    // TODO: 是否有必要对get参数进行处理？（等需求）

    $pathInfo = nbMvcHelper::getActionPathInfo($path);

    if (class_exists(ConvertTool::toPascal($pathInfo['module']).'Actions', false))
    {
      throw new nbAddonException('to the limit of php language, can not forward to a new module have the same name as the old one.');
    }

    nbAppHelper::setAppName($pathInfo['app']);
    nbRequest::getInstance()->setModuleName($pathInfo['module']);
    nbRequest::getInstance()->setActionName($pathInfo['action']);
    nbAppHelper::loadFrameworkApp('init');
    exit;
  }

  public function getBlock($path, array $parameters = array())
  {
    // TODO
  }

  public function includeBlock($path, array $parameters = array())
  {
    $blockClass = nbMvcHelper::executeBlock($path, $parameters);

    unset($componentClass->request);
    unset($componentClass->response);
    unset($componentClass->service);

    foreach ($componentClass as $name => $value)
    {
      $this->$name = $value;
    }
  }

  public function getComponent($path, array $parameters = array())
  {
    return nbWidget::getComponent($path, $parameters);
  }

  public function setVar($name, $value, $needEscape = true)
  {
    if (!$needEscape)
    {
      nbData::add('notEscape', $name, 'nb-mvc-notEscape');
    }
    $this->$name = $value;
  }
}