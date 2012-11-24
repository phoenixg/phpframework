<?php
// 表单的请求地址等信息
$config['nb-form/item']['toolAddonsForm']['formAttribute'] = array(
  'name' => 'toolAddonsForm',
  'method' => 'post',
  'action' => array('path' => 'default/submit'), // 传给的 nbWidget::url()，所以使用数组做参数
);

// 表单每一项的配置
//$config['nb-form/item']['toolAddonsForm']['columns']['id'] = array(
//  'function' => array(
//    'nbFormWidget::hidden' => array('id', '%id%', array('name' => 'id')) // 在编辑的时候，%id%会被替换为$editValue['id'] 的值
//  ),
//  'needBox' => false, // 表示是否需要显示为一行
//);
$config['nb-form/item']['toolAddonsForm']['columns']['username'] = array(
  'function' => array(
    'nbAppFormWidget::appPathSelect' => array('appName')
  ),
  'title' => 'Choose App', // 左侧显示的表单标题
  'help' => 'Please input you username here',
);
//$config['nb-form/item']['toolAddonsForm']['columns']['newPassword'] = array(
//  'function' => array(
//    'nbFormWidget::password' => array('newPassword')
//  ),
//  'title' => 'New Password',
//);
$config['nb-form/item']['toolAddonsForm']['columns']['submit'] = array(
  'function' => array(
    'nbFormWidget::submit' => array()
  ),
  'needTitle' => false, // 是否需要显示左侧的标题
);