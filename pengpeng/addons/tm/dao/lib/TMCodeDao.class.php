<?php
class TMCodeDao extends TMDao
{
  public function getCodeInfo($code)
  {
    return $this->selectOne('*', 'Tbl_Code', array('FCode' => $code));
  }

  public function useCode($code)
  {
    $uin = TMAuthUtils::getUin();
    $this->update('Tbl_Code', array('FStatus' => 1, 'FQQ' => $uin), array('FCode' => $code, 'FStatus' => 0));
  }
}