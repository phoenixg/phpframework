<?php
class CaseActions extends nbAction
{
  public function managerAction()
  {

  }

  public function addAction()
  {
    $this->options = $this->service->select('method_name', 'App_Tool_Test_Method');
  }
}