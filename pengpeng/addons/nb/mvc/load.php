<?php
//function stripslashes_deep($value)
//{
//    $value = is_array($value) ?
//                array_map('stripslashes_deep', $value) :
//                stripslashes($value);
//
//    return $value;
//}
//
//if((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase'))!="off")) ){
//    stripslashes_deep($_GET);
//    stripslashes_deep($_POST);
//    stripslashes_deep($_COOKIE);
//}

$controller = new nbController;
$controller->execute();