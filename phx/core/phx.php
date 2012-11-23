<?php
function __autoload($className)
{
new dBug($className);
	list($filename , $suffix) = explode('_' , $className);
	
	//compose file name
	$file = SERVER_ROOT . '/models/' . strtolower($filename) . '.php';
	
	//fetch file
	if (file_exists($file))
	{
		//get file
		include_once($file);		
	}
	else
	{
		//file does not exist!
		die("File '$filename' containing class '$className' not found.");	
	}
}
	
//fetch the passed request
$request = $_SERVER['QUERY_STRING'];

new dBug($request);

//parse the page request and other GET variables
$parsed = explode('&' , $request);

new dBug($parsed);

//the page is the first element
$page = array_shift($parsed);

new dBug($page);

//the rest of the array are get statements, parse them out.
$getVars = array();
foreach ($parsed as $argument)
{
	//split GET vars along '=' symbol to separate variable, values
	list($variable , $value) = explode('=' , $argument);
	$getVars[$variable] = $value;
}

new dBug($getVars);

//compute the path to the file
$target = SERVER_ROOT . '/controllers/' . $page . '.php';

new dBug($target);

//get target
if (file_exists($target))
{
	include_once($target);
	
	//modify page to fit naming convention
	$class = ucfirst($page) . '_Controller';
	
	//instantiate the appropriate class
	if (class_exists($class))
	{
		new dBug($class);
		$controller = new $class;
	}
	else
	{
		//did we name our class correctly?
		die('class does not exist!');
	}
}
else
{
	//can't find the file in 'controllers'! 
	die('page does not exist!');
}

new dBug($controller);

//once we have the controller instantiated, execute the default function
//pass any GET varaibles to the main method
$controller->main($getVars);

echo 'aa';

