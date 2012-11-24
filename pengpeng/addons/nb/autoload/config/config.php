<?php
/**
 * please don't modify this config file, copy it to PROJECT_ROOT . "/config/config.php";
 */

$config['saveFile'] = CACHE_ROOT.'autoload'.DIRECTORY_SEPARATOR.'Autoload.php';

//$finder = new nbFinder();
//$finder->setFormat('/\.class\.php$/');
$config['finderPara'] = array(
  'root' => array(ADDONS_ROOT, PROJECT_ROOT),
  'fileRegx' => '/\.class\.php$/',
);

$config['cache'] = true;