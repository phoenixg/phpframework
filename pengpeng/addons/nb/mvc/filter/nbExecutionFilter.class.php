<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbExecutionFilter extends nbFilter
{
  public function execute($filterChain)
  {
    $actionClass = $this->executeAction();

    $actionClass = $this->executeTemplate($actionClass);

    nbResponse::getInstance()->content = $this->executeLayout($actionClass);

    $filterChain->execute();
  }

  protected function executeAction()
  {
    $moduleName = nbRequest::getInstance()->getModuleName();
    $actionName = nbRequest::getInstance()->getActionName();

    $actionPath = nbAppHelper::getAppRoot()."modules".DIRECTORY_SEPARATOR.ucfirst($moduleName).".actions.php";

    new nbLog("Execute action $actionPath", 'aMvc');

    if (is_file($actionPath))
    {
      include $actionPath;
    }
    else
    {
      throw new nb404Exception('need action file at: ' . $actionPath);
    }

    $actionClassName = ucfirst($moduleName)."Actions";
    if (class_exists($actionClassName))
    {
      $actionClass = new $actionClassName();
    }
    else
    {
      throw new nbAddonException('class ' . $actionClassName . ' don\'t exist in file ' . $actionPath);
    }

    if (method_exists($actionClass, $actionName.'Action'))
    {
      call_user_func(array($actionClass, $actionName.'Action'));
    }
    else
    {
      throw new nbAddonException('method ' . $actionName.'Action don\'t exist in class ' . $actionClassName);
    }

    return $actionClass;
  }

  protected function executeTemplate($actionClass)
  {
    $moduleName = nbRequest::getInstance()->getModuleName();
    $actionName = nbRequest::getInstance()->getActionName();

    if (!$actionClass->getTemplate())
    {
      $templatePath = nbAppHelper::getAppRoot()."templates".DIRECTORY_SEPARATOR.$moduleName.DIRECTORY_SEPARATOR."$actionName.php";
    }
    else
    {
      $template = $actionClass->getTemplate();
      $pathInfo = nbMvcHelper::getTemplatePathInfo($template);
      $templatePath = $pathInfo['appRoot']."templates".DIRECTORY_SEPARATOR.$pathInfo['module'].DIRECTORY_SEPARATOR.$pathInfo['action'].".php";
    }

    new nbLog("Execute template $templatePath", 'aMvc');

    foreach ($actionClass as $name => $value)
    {
      if (!in_array($name, nbData::get('notEscape', 'nb-mvc-notEscape')))
      {
        $actionClass->$name = $this->htmlspecialchars($value);
      }
    }

    $actionClass->contents = $this->executeSubTemplate($templatePath, $actionClass);

    return $actionClass;
  }

  protected function executeLayout($actionClass)
  {
    if ($actionClass->getLayout() === false)
    {
      return $actionClass->contents;
    }

    $layout = $actionClass->getLayout() ? $actionClass->getLayout() : nbAppHelper::getCurrentAppConfig('defaultLayout', __FILE__);

    $pathInfo = nbMvcHelper::getLayoutPathInfo($layout);

    $layoutPath = $pathInfo['appRoot'].'layout'.DIRECTORY_SEPARATOR.$pathInfo['file'].'.php';

    new nbLog("Execute layout $layoutPath", 'aMvc');

    return $this->executeSubTemplate($layoutPath, $actionClass);
  }

  private function executeSubTemplate($__templatePath, $actionClass)
  {
    if (!is_file($__templatePath))
    {
      throw new nbAddonException('Need Template File '.$__templatePath);
    }

    foreach ($actionClass as $__name => $__value)
    {
      $$__name = $__value;
    }

    ob_start();
    include($__templatePath);
    $contents = ob_get_contents();
    ob_end_clean();

    return $contents;
  }

  private function htmlspecialchars($value)
  {
    if (is_string($value))
    {
      return htmlspecialchars($value, ENT_QUOTES);
    }
    else if (is_array($value))
    {
      foreach ($value as $key => $subValue)
      {
        $value[$key] = $this->htmlspecialchars($subValue, ENT_QUOTES);
      }

      return $value;
    }
    else
    {
      return $value;
    }
  }
}
