<?php
class DefaultActions extends nbAction
{
  public function indexAction()
  {
    $this->test = 'Hello World!';
  }
}