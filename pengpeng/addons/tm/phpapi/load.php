<?php
require_once "api.config.php";

define( 'PL2_SESS_NAME_UIN' , 'uin');
define( 'PL2_SESS_NAME_KEY' , 'skey');
define( 'PL2_VC_NAME_SESSION', "verifysession");

if (isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] != 'production' && $_SERVER['SERVER_TYPE'] != 'beta')
{
    //开发机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER1_PORT', 58001 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_VKEY_SERVER2_PORT', 58001 );

    define( 'PL2_SESS_SERVER1_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER1_PORT', 58000 );
    define( 'PL2_SESS_SERVER2_HOST', '172.25.38.16' );
    define( 'PL2_SESS_SERVER2_PORT', 58000 );
} else {
    //运营机模式
    define( 'PL2_VKEY_SERVER1_HOST', '172.23.32.42' );
    define( 'PL2_VKEY_SERVER1_PORT', 18888 );
    define( 'PL2_VKEY_SERVER2_HOST', '172.23.32.44' );
    define( 'PL2_VKEY_SERVER2_PORT', 18888 );

    define( 'PL2_SESS_SERVER1_HOST', '172.16.85.48' );
    define( 'PL2_SESS_SERVER1_PORT', 18891 );
    define( 'PL2_SESS_SERVER2_HOST', '10.134.8.30' );
    define( 'PL2_SESS_SERVER2_PORT', 18891 );
}
define( 'PL2_VKEY_VALIDTIME', 1800 );


//php api base path
$apiBasePath = dirname(__FILE__);

//env include file map
$apiIncludeFilesMap = array(
    'localhost'=>array(
        $apiBasePath."/dev/TMAuthUtils.class.php",
        $apiBasePath."/dev/TMValidPic.class.php",
        $apiBasePath."/dev/TMSendUtils.class.php",
    ),
    'dev'=>array(
        $apiBasePath."/dev/TMAuthUtils.class.php",
        $apiBasePath."/dev/TMValidPic.class.php",
        $apiBasePath."/dev/TMSendUtils.class.php",
    ),
    'test'=>array(
        $apiBasePath."/tencent/qp_misc.inc.php",
        $apiBasePath."/dev/TMAuthUtils.class.php",
        $apiBasePath."/dev/TMValidPic.class.php",
        $apiBasePath."/dev/TMSendUtils.class.php",
    ),
    'production'=>array(
        //$apiBasePath."/tencent/qp_misc.inc.php",
        $apiBasePath."/tencent/TMAuthUtils.class.php",
        $apiBasePath."/tencent/TMValidPic.class.php",
        $apiBasePath."/tencent/TMSendUtils.class.php",
    ),
    'beta'=>array(
        //$apiBasePath."/tencent/qp_misc.inc.php",
        $apiBasePath."/tencent/TMAuthUtils.class.php",
        $apiBasePath."/tencent/TMValidPic.class.php",
        $apiBasePath."/tencent/TMSendUtils.class.php",
    ),
);

$_SERVER['APPID'] = nbHelper::getConfig('minisite/appid');
isset($_SERVER['APPID']) ? "" : die(__FILE__.":请在api.config.php中初始化的APPID");
isset($apiIncludeFilesMap[$_SERVER['SERVER_TYPE']]) ? "" : die(__FILE__.":请在api.config.php中设定正确的SERVER_TYPE");

$apiIncludeFiles = $apiIncludeFilesMap[$_SERVER['SERVER_TYPE']];
foreach($apiIncludeFiles as $includeFile){
    require_once $includeFile;
}