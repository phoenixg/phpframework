<?php
class myException extends Exception {
	/*
	public function __construct($message = null, $code = 0, $file='./'){
		echo $file;
		parent::__construct($message, $code);
	}
	*/
	
	//返回异常信息
	public function getMessage(){
		echo 'dog';
	}

	//返回异常代码
	public function getCode(){

	}

	//返回异常抛出的位置
	public function getFile(){

	}

	//返回异常抛出的行号
	public function getLine(){

	}

	//返回异常回溯（数组）
	public function getTrace(){

	}

	//返回异常回溯（字符串）
	public function getTraceAsString(){

	}

	//返回异常信息（字符串）
	public function __toString(){

	}

}

/*
try {
	// 如果这里面抛出了异常，就直接跳到catch里面执行
	throw new Exception('这里是异常信息');
	echo '这里不会执行到';
} catch (Exception $e) {
	// 输出： exception 'Exception' with message '这里是错误信息' in D:\xampp\htdocs\phpframework\phx\test\test.php:6 Stack trace: #0 {main}
	echo $e->getMessage();
}
*/
set_exception_handler('myException');

try {
	throw new Exception('这里是异常信息');
	echo '这里不会执行到';
} catch (myException $e) {
	echo $e->getMessage();
}



