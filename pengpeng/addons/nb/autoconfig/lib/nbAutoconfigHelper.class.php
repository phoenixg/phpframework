<?php
class nbAutoconfigHelper
{
  public static function buildConfigCache($rebuild = false)
  {
    include(FRAMEWORK_ROOT.'autoconfig/config/config.php');
    $cacheFile = $config['saveFile'];

    if ($rebuild || !is_file($cacheFile))
    {
      $autoconfig = new nbAutoconfig();
      $autoconfig->execute();
    }
  }
}