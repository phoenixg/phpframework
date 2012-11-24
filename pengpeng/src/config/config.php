<?php

$config['cs-dao/db'] = 'mysql';
$config['cs-dao/host'] = 'localhost';
$config['cs-dao/user'] = 'root';
$config['cs-dao/password'] = '';
$config['cs-dao/dbname'] = 'bughelper';
$config['cs-dao/encoding'] = 'utf8';
$config['cs-dao/persistent'] = true;

$config['nb/doorOpened'] = array(
    'default.php',
    'tool-insert.php',
    'tool-select.php',
    'tool-checker.php',
    'tool-build.php',
);

$config['nb-mvc/filter'] = array(
    'nbRenderingFilter',
    // 'CsDebugFilter',
    'nbExecutionFilter',
);