<?php
class Default_Controller extends Controller {
	public function action_index()
	{
		echo 'you are in default controller and default method';
	}

	public function action_hello()
	{

		$modelPath = dirname(__FILE__).'/../models/user.php';
		if(!file_exists($modelPath)) 
			throw new Exception('不存在模型文件：'.$modelPath);
		
		include $modelPath;
		$model = new User_Model();
		$var1 =  $model->query();
		var_dump($var1);

		$viewPath = dirname(__FILE__) . '/../views/default.php';
		if(!file_exists($viewPath)) 
			throw new Exception('不存在视图文件：'.$viewPath);

		include $viewPath;
	}



}
