<?php
$test = new nbUnitTest();
$test->needFake();
$test->setMethod('nbToolHelper::getIp');
$test->assertEaque(array(), '127.0.0.1');



//$tester['nbToolHelper::getIp'] = array(
//  array('para' => array(), 'return' => '127.0.0.1'),
//);
//$tester['nbAppHelper::getAppName'] = array(
//  array('para' => array(), 'return' => 'tool-test'),
//);
//$tester['nbAppHelper::getAppRoot'] = array(
//  array('para' => array(), 'return' => 'tool-test'),
//);
