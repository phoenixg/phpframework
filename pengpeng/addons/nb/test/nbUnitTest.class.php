<?php
class nbUnitTest
{
  private $method;
  private $fake = false;

  public function setMethod($method)
  {
    $this->method = $method;
  }

  public function assertEaque($para, $returnValue)
  {
    list($className, $methodName) = explode('::', $this->method);

    if ($this->fake)
    {
      $className = $this->doFake($className);
    }

    $return = call_user_func_array(array($className, $methodName), $para);
    if ($return == $returnValue)
    {
      echo '.';
    }
    else
    {
      echo 'F';
    }
  }

  public function needFake()
  {
    $this->fake = true;
  }

  private function doFake($className)
  {
    $cacheClassPath = CACHE_ROOT.'test/'.$className.'Faker.class.php';
    if (!is_file($cacheClassPath))
    {
      $cachePath = nbHelper::getConfig('nb-autoload/saveFile');
      $filePath = include $cachePath;

      $classPath = $filePath[$className];
      $this->fakeFile($className, $classPath);
    }

    return $className.'Faker';
  }

  private function fakeFile($className, $classPath)
  {
    $content = file_get_contents($classPath);
    $content = preg_replace("/class $className/", "class {$className}Faker", $content);
    $content = preg_replace('/protected/', "public", $content);
    $content = preg_replace('/private/', "public", $content);

    $generate = new nbGenerate();
    $cacheFile = CACHE_ROOT.'test/'.$className.'Faker.class.php';
    $generate->writeFile($cacheFile, $content);
    include_once $cacheFile;
  }
}