<?php
$config['strategies']['isInt'] = array(
  'method' => 'nbValidate::isInt',
  //'exception' => 'the parameter must be int',
);
$config['strategies']['haveValue'] = array(
  'method' => 'nbValidate::haveValue',
  //'exception' => 'the parameter cant be empty',
);
$config['strategies']['inArray'] = array(
  'method' => 'nbValidate::inArray',
  //'exception' => 'the parameter must be in array',
  'para' => array('top', 'latest1', 'qq'),
);