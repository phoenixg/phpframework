<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbAutoconfig
{
  private $contents;

  public function execute()
  {
    $this->contents = '';

    $finder = new nbFinder();
    $para['root'] = array(FRAMEWORK_ROOT, ADDONS_ROOT, PROJECT_ROOT);
    $para['fileName'] = array('door.php', 'config.php');

    $para['ignorePath'] = array(CACHE_ROOT, PROJECT_ROOT.'config'.DIRECTORY_SEPARATOR);
    $appFiles = $finder->execute($para);

    $this->prepareAppRoots($appFiles);
    $this->prepareAppConfigTime($appFiles);
    $this->prepareAppConfigCache($appFiles);

    $config = nbConfigHelper::getConfigFile(dirname(__FILE__).'/../config/config.php');

    $generate = new nbGenerate();
    $generate->writeFile($config['saveFile'], "<?php\n".$this->contents);
  }

  private function prepareAppRoots($appFiles)
  {
    $appNames = array();

    foreach ($appFiles as $file)
    {
      $temp = str_replace(ADDONS_ROOT, '', $file);
      $temp = str_replace(FRAMEWORK_ROOT, '', $temp);
      $temp = str_replace(PROJECT_ROOT.'apps'.DIRECTORY_SEPARATOR, '', $temp);

      $temp = str_replace(DIRECTORY_SEPARATOR.'door.php', '', $temp);
      $temp = str_replace(DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php', '', $temp);
      $appName = str_replace(DIRECTORY_SEPARATOR, '-', $temp);

      $temp = str_replace(DIRECTORY_SEPARATOR.'door.php', '', $file);
      $appRoot = str_replace(DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php', '', $temp);

      if (!in_array($appName, $appNames))
      {
        $appRoots[$appName] = $appRoot;
      }
    }
    krsort($appRoots);

    nbConfigHelper::setConfig('nb/appRoots', $appRoots);

    $this->contents .= '$'."config['nb/appRoots'] = ";
    $this->contents .= str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, var_export($appRoots, true));
    $this->contents .= ";\n";
  }

  private function prepareAppConfigTime($appFiles)
  {
    $appNames = array();

    foreach ($appFiles as $file)
    {
      if (basename($file) == 'config.php')
      {
        $appConfigTimes[nbAppHelper::getAppNameByFilePath($file)] = filemtime($file);
      }
    }
    krsort($appConfigTimes);

    nbConfigHelper::setConfig('nb/appConfigTimes', $appConfigTimes);

    $this->contents .= '$'."config['nb/appConfigTimes'] = ";
    $this->contents .= str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, var_export($appConfigTimes, true));
    $this->contents .= ";\n";
  }

  private function prepareAppConfigCache($appFiles)
  {
    foreach ($appFiles as $file)
    {
      if (strstr($file, 'door.php'))
      {
        continue;
      }


      //include_once($file);

      $appName = nbAppHelper::getAppNameByFilePath($file);

      $tmep = file_get_contents($file);
      $tmep = preg_replace('/^<\?php/', '', $tmep);
      $tmep = preg_replace('/(\n\s)*(\?>)*(\n\s)*$/U', '', $tmep);
      if (preg_match('/[^;]$/', $tmep) && preg_match('/\$config/', $tmep))
      {
        $tmep = preg_replace('/$/D', ';', $tmep);
      }

      if (isset($nameSpace) && $nameSpace)
      {
        $tmep = preg_replace('/(\$config\[\')([\w-]*)(\'\])/', '\1'.$nameSpace.'/\2\3', $tmep);
      }
      else
      {
        $tmep = preg_replace('/(\$config\[\')([\w-]*)(\'\])/', '\1'.$appName.'/\2\3', $tmep);
      }

      $this->contents .= "\n/**\n * config from $file \n */".$tmep;
      unset($config);
      unset($nameSpace);
    }
  }
}