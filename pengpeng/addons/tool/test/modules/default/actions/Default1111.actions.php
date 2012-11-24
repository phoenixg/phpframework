<?php
class DefaultActions extends BaseAction
{
  public function indexAction()
  {
    $this->forward('test', 'unit');
  }
}