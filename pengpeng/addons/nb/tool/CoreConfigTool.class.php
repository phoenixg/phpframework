<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class aaaaaConfigTool
{
  static public function getConfig($name)
  {
    //p(AppConfig::getAll());

    if (AppConfig::has($name))
    {
      return AppConfig::get($name);
    }
    else
    {
      $autoconfig = new CoreAutoconfig();
      $autoconfig->execute();

      CoreAutoconfigTool::loadConfig(self::getConfig('core-autoconfig/saveFile'));

      if (AppConfig::has($name))
      {
        return AppConfig::get($name);
      }
      else
      {
        $log = new nbLog("cant find a addon config called '$name'");
        throw new nbCoreException("cant find a addon config called '$name'");
      }

    }
  }

  static public function getAllConfig()
  {
    return AppConfig::getAll();
  }
}