<?php
$config['tool-build/item']['nb-project']['title'] = '创建nb项目';
$config['tool-build/item']['nb-project']['description'] = '创建一个基础的项目';


$config['tool-build/item']['nb-project']['fromPath'] = array(
  ADDONS_ROOT.'nb/build/projectTemplate/',
);

$config['tool-build/item']['nb-project']['toPath'] = array(
  PROJECT_ROOT,
);

$config['tool-build/item']['nb-app']['title'] = '创建nb应用';
$config['tool-build/item']['nb-app']['description'] = '创建一个基础的应用';

$config['tool-build/item']['nb-app']['fromPath'] = array(
  ADDONS_ROOT.'nb/build/templates/app/main/',
);

$config['tool-build/item']['nb-app']['toPath'] = array(
  PROJECT_ROOT.'..%appPath%%appNote%/',
);

$config['tool-build/item']['nb-app']['fromPara'] = array(
  'appNote' => array(
    'function' => array(
      'nbFormWidget::text' => array('appNote')
    ),
    'title' => '应用的位置（英文，多级目录请用"/"分隔）',
  ),
  'appPath' => array(
    'function' => array(
      'nbFormWidget::select' => array('appPath', '/src/apps/', array('/addons/' => '/addons/', '/src/apps/' => '/src/apps/'))
    ),
    'title' => '应用的根路径',
  ),
);