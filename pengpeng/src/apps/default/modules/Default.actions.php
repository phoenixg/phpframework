<?php
class DefaultActions extends nbAction
{
  public function indexAction()
  {
    $this->aaa = 1;//传值
	$result = $this->service->Select('*','bughelper_bugs',array(1=>1));//基本sql
	print_r($result);
  }
}

?>