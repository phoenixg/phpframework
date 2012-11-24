<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbGenerate
{
  public $generateLog = array();
  /**
   *
   * @param string $__templatePath
   * @param string $targetPath
   * @param array $vlaues
   * @return null
   */
  public function generateFile($__templatePath, $targetPath, $values = array())
  {
    $targetPath = preg_replace('/%(\w+)%/e', "\$this->replaceFileName('\\1', \$values)", $targetPath);

    if ($this->isDir($targetPath))
    {
      // must be a folder

      if (!file_exists($targetPath))
      {
        $old = umask(0);
        mkdir($targetPath, 0777, true);
        umask($old);
        new nbLog('Generate folder: '. $targetPath);
        //$this->generateLog[] = 'Generate folder: '. $targetPath;
      }
    }
    else
    {
      if (preg_match('/\.nbt$/', $targetPath, $matchs))
      {
        extract($values);

        ob_start();
        include($__templatePath);
        $content = ob_get_contents();
        ob_end_clean();

        $targetPath = preg_replace('/\.nbt/', '', $targetPath);
        $this->writeFile($targetPath, $content, array('phpToken' => true));
      }
      else
      {
        $content = file_get_contents($__templatePath);
        $this->writeFile($targetPath, $content, array('phpToken' => false));
      }

      new nbLog('Generate folder: '. $targetPath);
      //$this->generateLog[] = 'Generate file: '. $targetPath;
    }
  }

  private function replaceFileName($name, $values)
  {
    if (isset($values[$name]))
    {
      return $values[$name];
    }
    else
    {
      return $name;
    }
  }

  private function isDir($file)
  {
    if (preg_match('/\/$/', $file))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public function writeFile($path, $content, array $options = array())
  {
    $pathParts = pathinfo($path);

    $old = umask(0);
    if (!is_dir($pathParts['dirname']))
    {
      mkdir($pathParts['dirname'], 0777, true);
    }

    if (!file_exists($path))
    {
      touch($path);
    }

    if (isset($options['phpToken']) && $options['phpToken'])
    {
      $content = preg_replace(array('/\[\?php/', '/\?\]/'), array('<?php', '?>'), $content);
    }

    file_put_contents($path, $content);
    umask($old);
  }

  public function generateFolder($templatePath, $targetPath, $vlaues = array(), $finderPara = array())
  {
    $finderPara['root'] = $templatePath;
    $finderPara['fileRegx'] = '/.*/';
    $finder = new nbFinder();
    $this->allGenerateFile = $finder->execute($finderPara);

    foreach ($this->allGenerateFile as $filePath)
    {
      $notePath = str_replace($templatePath, '', $filePath);
      $this->generateFile($templatePath.$notePath, $targetPath.$notePath, $vlaues);
    }
  }

  /**
   * All file under dir would be return, without folder
   *
   * @param string $dir
   * @return array
   */
  public function getAllFileInDir($dir, $subDir = '')
  {
    $files = array();
    $fp = opendir($dir);
    while (($file = readdir($fp)) !== false)
    {
      if ($file == "." || $file == ".." || $file == ".svn")
      {
        continue;
      }
      else
      {
        if (is_dir($dir.$file))
        {
          $files[] = $subDir.$file.'/';
          $files = array_merge($files, $this->getAllFileInDir($dir.$file.'/', $subDir.$file.'/'));
        }
        else
        {
          $files[] = $subDir.$file;
        }
      }
    }

    return $files;
  }
}
?>