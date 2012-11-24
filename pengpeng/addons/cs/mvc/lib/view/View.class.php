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
class View
{
  /**
   *
   * @param string $templateName
   * @param array $vars
   * @return void
   */
  public static function includePartial($templateName, array $vars = array())
  {
    echo self::getPartial($templateName, $vars);
  }

  /**
   *
   * @param string $templateName
   * @param array $vars
   * @return string
   */
  public static function getPartial($templateName, $vars = array())
  {
    // partial is in another module?
    if (false !== $sep = strpos($templateName, '/'))
    {
      $moduleName = substr($templateName, 0, $sep);
      $templateName = substr($templateName, $sep + 1);
    }
    else
    {
      $moduleName = Request::getInstance()->getModuleName();
    }
    // the partial name have a '_' before it
    $actionName = '_'.$templateName;

    foreach ($vars as $name => $var)
    {
      $$name = $var;
    }

    $path = nbAppHelper::getAppRoot().'modules/'.$moduleName.'/templates/_'.$templateName.'.php';
    ob_start();
    include($path);
    $data = ob_get_contents();
    ob_end_clean();

    return $data;
  }
}