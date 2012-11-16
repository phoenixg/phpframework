<?php defined('DS') or die('Direct access forbid!');

function custom_error_handler($errno, $errstr, $errfile, $errline)
{
	$log_file = "PHP_Log_%s_" . date("Ymd") . ".log";
	$now = date("Y-m-d H:i:s");
	$template_str = "[$now] %s on line $errline in file $errfile: $errstr\n";
	$log_str = '';
	switch($errno){
		case E_USER_ERROR:
			$log_file = sprintf($log_file, "Error");
			$log_str = sprintf($template_str, "Fatal error");
			break;
		case E_USER_WARNING:
			$log_file = sprintf($log_file, "Warning");
			$log_str = sprintf($template_str, "Warning");
			break;
		case E_USER_NOTICE:
			$log_file = sprintf($log_file, "Notice");
			$log_str = sprintf($template_str, "Notice");
			break;
	}
	if($log_str != ''){
		file_put_contents($log_file, $log_str, FILE_APPEND);
	}

	return true;
}

//定义警告函数  
function warn($arr)
{
	if(!is_array($arr) || emptyempty($arr)){
		trigger_error("Input variable not an array", E_USER_WARNING);
		return false;
	}
	echo 'OK';
	return true;
}

//定义提示函数  
function notice($num)
{
	if(!is_int($num)){
		trigger_error("Input variable not an int", E_USER_NOTICE);
		return false;
	}
	echo 'OK';
	return true;
}


//运行该函数将利用trigger_error()手动触发自定义的错误处理函数，该错误处理函数会把PHP系统错误记录成日志  
//运行结果，当前目录下新生成了两个文件：（以后每出一次错都会新增一条日志记录）  
/* 
PHP_Log_Notice_20121013.log ： 
 [2012-10-13 13:58:14] Notice on line 46 in file /home/phoenix/public_html/gitprojects/laravel/sandbox/error.php: Input variable not an int 
PHP_Log_Warning_20121013.log ： 
 [2012-10-13 13:58:14] Warning on line 36 in file /home/phoenix/public_html/gitprojects/laravel/sandbox/error.php: Input variable not an array 
*/  
warn('test');  
notice('test');  