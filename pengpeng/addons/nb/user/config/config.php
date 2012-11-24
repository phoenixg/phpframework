<?php
$config['table'] = 'CS_User';
$config['idColumn'] = 'id';
$config['nameColumn'] = 'username';
$config['passwordColumn'] = 'password';
$config['passwordEscapeMethod'] = 'nbUserHelper::md5';
$config['loginCookieName'] = 'nbUserLogin';
$config['loginCookieValueMethod'] = 'nbUserHelper::md5';

$config['item']['system']['role'] = array(
  'manager' => array('name' => '系统管理员', 'description' => '系统的管理员，拥有最高的管理权限')
);

$config['item']['system']['rolePrivilege'] = array(
  array('role' => 'manager', 'privilege' => '*')
);

$config['item']['csUser'] = array(
  'role' => array(
    'manager' => array('name' => '权限系统管理员', 'description' => '权限系统的管理员'),
    'user' => array('name' => '权限系统用户')
  ),
  'rolePrivilege' => array(
    'manager' => array('view', 'operate', 'test'),
    'user' => array('view'),
  ),
  'privilege' => array(
    'view' => array('name' => '查看权限系统', 'description' => '可以查看权限系统'),
    'operate' => array('name' => '操作权限系统'),
    'test' => array('name' => 'test3'),
  )
);