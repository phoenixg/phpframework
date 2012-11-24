<?php
abstract class CsDao extends nbService
{
  public function __construct()
  {
    parent::__construct();
    $this->setTable();
  }

  abstract public function setTable();
}