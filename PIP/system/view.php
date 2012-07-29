<?php

class View {

	//页面中的变量
	private $pageVars = array();

	//页面模板文件
	private $template;

	//加载页面模板文件
	public function __construct($template)
	{
		$this->template = APP_DIR .'views/'. $template .'.php';
	}

	//为页面的变量赋值
	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}


	//输出页面
	public function render()
	{
		extract($this->pageVars);

		ob_start();
		require($this->template);
		echo ob_get_clean();
	}
    
}

?>