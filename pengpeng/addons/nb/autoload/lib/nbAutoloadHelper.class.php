<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbAutoloadHelper
{
  private static $autoloadPaths = array();

  public static function getClassPath($className)
  {
    if (!self::$autoloadPaths)
    {
      self::loadAutoloadFile();
    }

    if (isset(self::$autoloadPaths[$className]) && is_file(self::$autoloadPaths[$className]))
    {
      include_once(self::$autoloadPaths[$className]);
    }
    else
    {
      $autoload = new nbAutoload();
      $autoload->execute();
      new nbLog("generate autoload for $className");
      self::loadAutoloadFile();
    }

    if (isset(self::$autoloadPaths[$className]))
    {
      include_once(self::$autoloadPaths[$className]);
    }
    else
    {
      return false;
    }
  }

  private static function loadAutoloadFile()
  {
    $path = nbAppHelper::getCurrentAppConfig('saveFile', __FILE__);
    self::$autoloadPaths = include($path);
  }
}