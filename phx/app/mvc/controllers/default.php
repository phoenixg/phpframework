<?php
class Default_Controller extends Controller {
	public function action_index()
	{
		echo 'you are in default controller and default method';
	}

	public function action_hello()
	{
		echo 'you are in default controller and hello method';
		$this->_display('default.php');
	}



}
