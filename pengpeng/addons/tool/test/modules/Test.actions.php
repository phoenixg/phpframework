<?php
class TestActions extends nbAction
{
  public function unitAction()
  {
    $testFiles = CsTestTool::getTestFiles();

    foreach ($testFiles as $testFile)
    {
      include_once($testFile);
      $fileContents = file_get_contents($testFile);

      preg_match('/class (\w*)/', $fileContents, $matchClasses);

      $object = new $matchClasses[1];

      $r = new ReflectionClass($object);

      echo '<table><tr><td width="150px">'.$r->getName().':</td><td>';
      foreach ($r->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
      {
        $methodName = $method->name;
        if (preg_match('/^test/', $methodName))
        {
          $object->$methodName();
        }
      }
      echo "</td></tr></table>";

    }
  }

  public function functionalAction()
  {
    $testFiles = readFilesRecursive(PROJECT_ROOT.'test/functional/', '/test.php/');

    foreach ($testFiles as $testFile)
    {
      include_once($testFile);
      $fileContents = file_get_contents($testFile);

      preg_match('/class (\w*)/', $fileContents, $matchClasses);

      $object = new $matchClasses[1];

      $r = new ReflectionClass($object);

      echo '<table><tr><td width="150px">'.$r->getName().':</td><td>';
      foreach ($r->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
      {
        $methodName = $method->name;
        if (preg_match('/^test/', $methodName))
        {
          $object->$methodName();
        }
      }
      echo "</td></tr></table>";

    }
  }
}