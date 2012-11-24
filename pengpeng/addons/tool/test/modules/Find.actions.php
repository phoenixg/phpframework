<?php
class FindActions extends nbAction
{
  public function classAction()
  {
    $finder = new nbFinder();
    $return = $finder->execute(array('fileRegx' => '/\.class\.php/'));

    foreach ($return as $class)
    {
      $className = basename($class, ".class.php");

      //include_once($class);

      //$object = new $className;

      $r = new ReflectionClass($className);

      foreach ($r->getMethods() as $method)
      {
        $parameters = new ReflectionMethod($className, $method->getName());
        $this->service->insert('App_Tool_Test_Method', array(
          'method_name' => "$className/".$method->getName(),
          'parameter_number' => $parameters->getNumberOfParameters(),
          'parameter_value' => serialize($parameters->getParameters()),
          ));
      }
    }
  }

  public function testAction()
  {
    $finder = new nbFinder();
    $returns = $finder->execute(array('fileRegx' => '/tester\.php/'));

    foreach ($returns as $return)
    {
      include $return;
    }

//    foreach ($tester as $method => $cases)
//    {
//      list($className, $methodName) = explode('::', $method);
//      echo $method.' :';
//
//      foreach ($cases as $case)
//      {
//        $return = call_user_func_array(array($className, $methodName), $case['para']);
//        if ($return == $case['return'])
//        {
//          echo '.';
//        }
//        else
//        {
//          echo 'F';
//        }
//      }
//      echo '<br />';
//    }
    exit;
  }
}