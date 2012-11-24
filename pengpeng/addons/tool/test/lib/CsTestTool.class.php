<?php
class CsTestTool
{
  public static function getTestFiles()
  {
    $finder = new nbFinder();
    $return = $finder->execute(array('fileRegx' => '/\.test\.php/'));

    return $return;
  }
}