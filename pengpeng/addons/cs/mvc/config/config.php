<?php
/**
 * please don't modify this config file, copy it to PROJECT_ROOT . "/config/config.php";
 */

$config['db'] = array(
  'db' => 'mysql',
  'host' => 'localhost',
  'user' => 'root',
  'password' => '',
  'dbname' => '',
  'encoding' => 'utf8',
  'persistent' => true,
);

$config['factories'] = array(
  'controller' => 'Control',
  'request' => 'Request',
  'response' => 'Response',
  'log' => 'CsLog',
);

$config['filters'] = array(
  'RenderFilter',
  'ExecuteFilter'
);

$config['preActionRules'] = array(
  // '/.*/' => 'preExecute',
);

$config['postActionRules'] = array(
  // '/.*/' => 'postExecute',
);

// used for debug
$config['coreCache'] = true;

//$config['globalLayout'] = 'global';
$config['layout'] = 'default';
$config['defaultApp'] = 'index';
$config['templates'] = 'templates';
$config['defaultModule'] = 'default';
$config['defaultAction'] = 'index';