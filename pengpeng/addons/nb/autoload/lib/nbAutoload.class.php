<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbAutoload
{
  private $includeFile = array();

  public function execute()
  {
    $this->config = nbConfigHelper::getConfigFile(dirname(__FILE__).'/../config/config.php');

    $saveFile = $this->config['saveFile'];

    if (file_exists($saveFile))
    {
      unlink($saveFile);
    }

    $this->commit();
  }

  private function commit()
  {
    $files = array();

    $finder = new nbFinder();

    foreach ($finder->execute($this->config['finderPara']) as $key => $file)
    {
      $className = basename($file, ".class.php");
      $files[$className] = $file;
    }

    ksort($files);

    $generate = new nbGenerate();
    $generate->writeFile($this->config['saveFile'], "<?php\nreturn ".str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, var_export($files, true)).';');
  }
}