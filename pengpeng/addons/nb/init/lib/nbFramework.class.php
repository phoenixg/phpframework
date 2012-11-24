<?php

/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbFramework
{
  public function init()
  {
    try
    {
    	// 载入函数
      $this->initCoreFunction();

      // 载入所有配置文件
      $this->initAutoconfig();

      // 载入 Autoload
      $this->initAutoload();

      // 检测App的依赖关系
      $this->examAppDepandends();

      // 框架运行完毕，开始运行App
      $this->knockKnockKnock();
    }
    catch (nb404Exception $e)
    {
      echo (SERVER_LEVEL < 75) ? $e->getMessage() : '';
    }
    catch (nbCoreException $e)
    {
      echo (SERVER_LEVEL < 75) ? $e->getMessage() : '';
    }
    catch (nbAddonException $e)
    {
      echo (SERVER_LEVEL < 75) ? $e->getMessage() : '';
    }
    catch (nbMessageException $e)
    {
      echo (SERVER_LEVEL < 75) ? $e->getMessage() : '';
    }
  }

  private function initCoreFunction()
  {
    if (is_file(FRAMEWORK_ROOT.'function/function.php'))
    {
      include_once(FRAMEWORK_ROOT.'function/function.php');
    }
    else
    {
      throw new nbCoreException('need '.FRAMEWORK_ROOT.'function/core.function.php');
    }
  }


  private function initAutoconfig()
  {
    nbAppHelper::loadFrameworkApp('autoconfig');

    if (!nbHelper::getConfig('nb-autoconfig/cache'))
    {
      unlink(nbHelper::getConfig('nb-autoconfig/saveFile'));
      nbAppHelper::loadFrameworkApp('autoconfig');
    }
  }

  private function initAutoload()
  {
    nbAppHelper::loadFrameworkApp('autoload');

    if (!nbHelper::getConfig('nb-autoload/cache'))
    {
      unlink(nbHelper::getConfig('nb-autoload/saveFile'));
      nbAppHelper::loadFrameworkApp('autoload');
    }
  }

  private function examAppDepandends()
  {
    $configInfo = nbData::getAll('nb-config');

    foreach ($configInfo as $configKey => $configValues)
    {
      if (strpos($configKey, 'depends'))
      {
        foreach ($configValues as $configValue)
        {
          if (!in_array($configValue, nbHelper::getConfig('nb/appNames')))
          {
            throw new nbCoreException("need to install addon '$configValue'");
          }
        }
      }
    }
  }

  private function knockKnockKnock()
  {
    if (!file_exists(nbAppHelper::getAppRoot().'door.php'))
    {
      throw new nbCoreException("App '".nbAppHelper::getAppName()."' missing! You need the create a app called '".nbAppHelper::getAppName()."'.");
    }

    if (!in_array(nbAppHelper::getAppName().'.php', (array) nbHelper::getConfig('nb/doorOpened')))
    {
      throw new nbCoreException(array('this app called %appName%.php is not allowed to view', array('appName' => nbAppHelper::getAppName())));
    }

    include(nbAppHelper::getAppRoot().'door.php');
  }


}