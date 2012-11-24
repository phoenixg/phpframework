<?php
class Criteria
{
  protected $criteria = array();

  public function getCriteria()
  {
    return $this->criteria;
  }

  public function clear()
  {
    $this->criteria = array();
  }
}
