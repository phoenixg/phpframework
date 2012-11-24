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
class Control
{
  /**
   *
   * @param string $env
   * @param string $debug
   * @param string $app
   * @return void
   */
  public function execute($env, $debug, $app = '')
  {
    try
    {
      //$this->initApp($app);

      FilterChain::getInstance()->startFilterChain();
    }
    catch (MvcException $e)
    {
      echo $e->getMessage();
    }
    catch (Exception $e)
    {
      echo $e->getMessage();
    }
  }

  /**
   *
   * @param string $app
   * @return void
   */
  private function initApp($app)
  {
    if ($app)
    {
      define('APP', $app);
    }
    else if (preg_match('/^\/?(\w+)\.php/', $_SERVER['REQUEST_URI'], $matchs))
    {
      define('APP', $matchs[1]);
    }
    else
    {
      define('APP', ConfigTool::getConfig('cs-mvc/defaultApp'));
    }

    AppTracter::getInstance()->setStartApp(APP);
    AppTracter::getInstance()->addAppTracter(APP);

    define('APP_ROOT', getAppRoot(APP));
  }

}