<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbFinder
{
  private $para = array();
  private $returnFiles = array();

  public function execute($para)
  {
    $config = nbConfigHelper::getConfigFile(dirname(__FILE__).'/../config/config.php');
    $this->para = nbDataHelper::bindPara($para, array(
            'root',
            'ignoreFolder',
            'ignorePath',
            'ignoreFile',
            'fileRegx',
            'pathRegx',
            'fileName'));

//    $md5 = md5(strval(implode(', ', $this->para)));
//    $cachePath = CACHE_ROOT.'nbFinder'.DIRECTORY_SEPARATOR.$md5.'.txt';
//
//    if (is_file($cachePath))
//    {
//      return include $cachePath;
//    }
//    else
//    {
      if ($this->para['ignoreFolder'])
      {
        $this->para['ignoreFolder'] = array_merge((array)$this->para['ignoreFolder'], $config['ignoreFolder']);
      }
      else
      {
        $this->para['ignoreFolder'] = $config['ignoreFolder'];
      }

      $this->para['root'] = $this->para['root'] ? $this->para['root'] : $config['root'];

      foreach ((array)$this->para['root'] as $root)
      {
        $this->executeOneDir($root);
      }

      return array_unique($this->returnFiles);
//    }
  }

  private function executeOneDir($dir)
  {
    if (!is_dir($dir))
    {
      return;
    }

    if ($this->para['ignorePath'] && in_array($dir, (array)$this->para['ignorePath']))
    {
      return;
    }

    $handle = opendir($dir);
    while (false !== ($file = readdir($handle)))
    {
      if (!in_array($file, $this->para['ignoreFolder']))
      {
        //echo 1;
        if (is_file($dir.$file))
        {
          //echo 3;echo "<br />";echo $dir.$file;echo "<br />";
          if ($this->para['ignoreFile'] && in_array($file, (array)$this->para['ignoreFile']))
          {
            //echo 4;
            continue;
          }

          if ($this->para['fileName'] && in_array($file, (array)$this->para['fileName']))
          {
            //echo 5;
            $this->returnFiles[] = $dir.$file;
            continue;
          }

          if ($this->para['fileRegx'] && preg_match($this->para['fileRegx'], $file))
          {
            //echo 6;
            //p(7);
            $this->returnFiles[] = $dir.$file;
            continue;
          }

          // TODO : pathRegx
        }
        else
        {
          //echo 2;
          $this->executeOneDir($dir.$file.DIRECTORY_SEPARATOR);
        }
      }
      else
      {
        //echo 8;echo "<br />";echo $dir.$file;echo "<br />";
      }
    }
  }
}