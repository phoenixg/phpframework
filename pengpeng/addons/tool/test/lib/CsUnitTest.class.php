<?php
class CsUnitTest
{
  /**
   *
   * @param mix $value1
   * @param mix $value2
   * @param string $message
   * @return bool
   */
  public function assertEaque($value1, $value2, $message = '')
  {
    if ($value1 == $value2)
    {
      echo '.';

      return true;
    }
    else
    {
      echo "F\n";
      var_dump($value1)."\n";
      var_dump($value2)."\n";
    }
  }

  /**
   *
   * @param mix $value
   * @param string $type
   *
   * @return void
   */
  public function assertOut($value, $type = "regex")
  {
    //TODO
  }

  /**
   *
   * @param bool $value
   * @return bool|void
   */
  public function assertTrue($value)
  {
    if ($value)
    {
      echo '.';

      return true;
    }
    else
    {
      echo "F\n";
      var_dump($value)."\n";
    }
  }

  /**
   *
   * @param string $className
   * @param array $parameters
   * @return mix
   */
  public function fake($className, array $parameters = array())
  {
    $cachePath = CACHE_ROOT.'lib/'.$className.'Faker.class.php';
    $classPath = Autoload::getInstance()->getClassPath($className);
    if (file_exists($cachePath) && filemtime($cachePath) > filemtime($classPath))
    {
      $newClassName = $className.'Faker';

      return new $newClassName($parameters[0]);
    }
    else
    {
      $content = file_get_contents($classPath);
      $content = preg_replace("/class $className/", "class {$className}Faker", $content);
      $content = preg_replace('/protected/', "public", $content);
      $content = preg_replace('/private/', "public", $content);
      file_put_contents(CACHE_ROOT.'lib/'.$className.'Faker.class.php', $content);

      $autoload = Autoload::getInstance(true);

      $autoload->addDir(CACHE_ROOT.'lib/');
      $autoload->execute(true);

      $newClassName = $className.'Faker';

      return new $newClassName($parameters[0]);
    }
  }
}