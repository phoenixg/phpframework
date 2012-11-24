<?php
class Zipper extends ZipArchive
{
  public function addDir($path, $rootPath, $addPath = '')
  {
    //print 'adding ' . $path . '<br>';
    $this->addEmptyDir($addPath.str_replace($rootPath, '', $path));
    //print 'to ' . $addPath.str_replace($rootPath, '', $path) . '<br>';
    $nodes = glob($path . '/*');
    foreach ($nodes as $node) {
      //print $node . '<br>';
      if (is_dir($node)) {
          $this->addDir($node, $rootPath, $addPath);
      } else if (is_file($node))  {
          //print 'to ' . $addPath.str_replace($rootPath, '', $node) . '<br>';
          $this->addFile($node, $addPath.str_replace($rootPath, '', $node));
      }
    }
  }
}