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
class ExecuteFilter
{
  /**
   *
   * @param FilterChain $filterChain
   * @return unknown_type
   */
  public function execute(FilterChain $filterChain)
  {
    $actionClass = $this->executeAction();

    $actionClass = $this->executeTemplate($actionClass);

    $content = $this->executeLayout($actionClass);

    return $content;
  }

  protected function executeAction()
  {
    $request = nbRequest::getInstance();

    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();

    if (file_exists(PROJECT_ROOT.'config/header.php'))
    {
      include_once(PROJECT_ROOT.'config/header.php');
    }

    $path = nbAppHelper::getAppName()."/$moduleName/$actionName";
    $actionClass = ActionTool::executeForAction($path, 'action', $request);

    if (!$actionClass)
    {
      $actionPathSingle = PathTool::getActionFileByPath($path, 'action');
      $actionPathTogether = PathTool::getActionFileByPath($path, 'actions');
      throw new MvcException ("$actionPathSingle or $actionPathTogether doesn't exist");
    }

    return $actionClass;
  }

  protected function executeTemplate($actionClass)
  {
    $request = nbRequest::getInstance();
    $response = nbResponse::getInstance();

    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();

    if (file_exists(nbAppHelper::getAppRoot().'config/header.php'))
    {
      include_once(nbAppHelper::getAppRoot().'config/header.php');
    }

    $template = $actionClass->getTemplate() ? $actionClass->getTemplate() : nbAppHelper::getAppName()."/$moduleName/$actionName";

    $templatePath = PathTool::getTemplateFileByPath($template, 'action');

    $actionClass->contents = executeTemplate($templatePath, $actionClass);

    return $actionClass;
  }

  protected function executeLayout($actionClass)
  {
    return $this->executeAppLayout($actionClass);
    //$actionClass->setContents($contents);

    //$this->executeGlobalLayout($actionClass);
  }

  protected function executeAppLayout($actionClass)
  {
    $appLayout = $actionClass->getAppLayout() ? $actionClass->getAppLayout() : nbHelper::getConfig('cs-mvc/layout');

    $layoutPath = PathTool::getLayoutFileByPath($appLayout);

    return executeTemplate($layoutPath, $actionClass);
  }

  //  protected function executeGlobalLayout($actionClass)
  //  {
  //    $globalLayout = $actionClass->getGlobalLayout() ? $actionClass->getGlobalLayout() : ConfigTool::getConfig('cs/mvc/globalLayout');
  //
  //    $layoutPath = PathTool::getLayoutFileByPath($globalLayout);
  //
  //    return executeTemplate($layoutPath, $actionClass);
  //  }

}