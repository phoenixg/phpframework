<?php
define('PROJECT_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
define('FRAMEWORK_ROOT',
       realpath(dirname(__FILE__) .
                DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . 'addons' .
                DIRECTORY_SEPARATOR . 'nb') . DIRECTORY_SEPARATOR);

require_once(FRAMEWORK_ROOT . 'autoload' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nbInitAutoload.class.php');
nbInitAutoload::register();

nbHelper::loadApp('framework/coreConfig', array('coreConfigClass' => 'nbCoreConfig'));
nbHelper::loadApp('framework/init');