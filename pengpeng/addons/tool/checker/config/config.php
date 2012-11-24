<?php
/**
 * please don't modify this config file, copy it to PROJECT_ROOT . "/config/config.php";
 */
include_once(ADDONS_ROOT.'/tool/checker/config/checker.const.php');

// 控制在/web/目录下该APP的一级目录名，默认为该app的名字。
$config['app']['webname'] = '';

$config['appType'] = 'app';

// 三种值类型，boolean，字符串，正则
$config['rules'] = array(
  'bom' => array('/^'.CHECKER_BOM.'/', 'cant have BOM at the begining of an UTF-8 file'),
  'startOfPhp' => array('/<\?(?!php|xml)/', 'cant use <? , need to use <?php'),
  'endlineFormat' => array('/'.CHECKER_ONE_WINDOWS_END_LINE.'/', 'cant be windows end line format', 1),
  'tab' => array("/\n".CHECKER_MORE_THEN_ZERO_SPACE."\t/", 'please dont use tab'),
  'endlineSpace' => array('/' . CHECKER_ONE_BLANK . CHECKER_ONE_END_LINE . '/', 'Dont need space(s) at the end of this line', 1),
  'needSpace' => array('/\s(if|while|foreach|catch|for|switch)\(/', 'there should be a space between if|while|foreach|catch|for|switch|array and ('),
  'rightSpace' => array('/(,|\*|-(?!>|-|\d|;|=)|=>)(?!' . CHECKER_ONE_SPACES_OR_ONE_END_LINES . ')/', 'need right space after character ",", "*", "-", "=>"'),
);

$config['commentRules'] = array(
  'methodNeedComment' => array("/(?<!\/)\n".CHECKER_ONE_SPACE."*".CHECKER_METHOD_KEYWORD."function/", 'all class methods need comment', 1, 1),
);

$config['finderPara'] = array(
  'root' => array(ADDONS_ROOT, PROJECT_ROOT),
  'fileRegx' => '/\.(class|actions?)\.php/',
  'ignoreFolder' => array('tm'),
);

//$config['mvc']['globalLayout'] = 'hunter/app';