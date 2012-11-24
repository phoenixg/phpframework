<?php
class nbCoreConfig
{
  public function register()
  {
    $this->loadServerConfig();

    $this->loadDebugConfig();
    $this->loadTimeZoneConfig();
    $this->loadRootConstConfig();
    $this->loadHttpConstConfig();
  }

  protected function loadServerConfig()
  {
    $serverLevels = array(
      'localhost' => 10,
      'dev' => 10,
      'test' => 50,
      'beta' => 80,
      'production' => 100,
    );

    if (!defined('SERVER_TYPE'))
    {
      if (!isset($_SERVER['SERVER_TYPE']))
      {
        $_SERVER['SERVER_TYPE'] = "production";
      }
      define('SERVER_TYPE', $_SERVER['SERVER_TYPE']);
    }

    if (!defined('SERVER_LEVEL'))
    {
      define('SERVER_LEVEL', $serverLevels[$_SERVER['SERVER_TYPE']]);
    }
  }

  protected function loadDebugConfig()
  {
    if ($_SERVER['SERVER_TYPE'] == "production")
    {
      error_reporting(E_ALL);
      ini_set('display_errors', 'Off');
    }
    else
    {
      error_reporting(E_ALL);
      ini_set('display_errors', 'On');
    }
  }

  protected function loadTimeZoneConfig()
  {
    date_default_timezone_set('Asia/Shanghai');
  }

  protected function loadRootConstConfig()
  {
    if (!defined('ADDONS_ROOT'))
    {
      define('ADDONS_ROOT', realpath(PROJECT_ROOT.'..'.DIRECTORY_SEPARATOR.'addons').DIRECTORY_SEPARATOR);
    }

    if (!defined('CACHE_ROOT'))
    {
      define('CACHE_ROOT', PROJECT_ROOT.'cache'.DIRECTORY_SEPARATOR);
    }

    if (!defined('UPLOAD_ROOT'))
    {
      define('UPLOAD_ROOT', PROJECT_ROOT.'web'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR);
    }

    if (!defined('HTTP_ROOT'))
    {
      define('HTTP_ROOT', PROJECT_ROOT.'web'.DIRECTORY_SEPARATOR);
    }
  }

  protected function loadHttpConstConfig()
  {
    if ($_SERVER['SERVER_PORT'] != 80)
    {
      $port = ':'.$_SERVER['SERVER_PORT'];
    }
    else
    {
      $port = '';
    }

    if (!defined('URL_ROOT'))
    {
      define('URL_ROOT', "http://{$_SERVER['HTTP_HOST']}$port/");
    }

    if (!defined('UPLOAD_URL_ROOT'))
    {
      define('UPLOAD_URL_ROOT', URL_ROOT.'web'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR);
    }
  }
}