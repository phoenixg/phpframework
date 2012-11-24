<?php
class TMPincodeDao extends TMDao
{
  public function hasNoUsedPincode($pincode)
  {
    return $this->selectOne('*', 'Tbl_Code', array('FCode' => $pincode, 'FStatus' => 0));
  }

  public function usePincode($pincode)
  {
    $this->update('Tbl_Code', array('FStatus' => 1), array('FCode' => $pincode));
  }

  public function hasPincode($pincode)
  {
    return $this->selectOne('*', 'Tbl_Code', array('FCode' => $pincode));
  }

  public function pincodeType($pincode)
  {
    return $this->selectOne('FValue', 'Tbl_Code', array('FCode' => $pincode));
  }
  
  public function insertExchangeHistory($pincode, $exchangeType, $limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $qq = TMAuthUtils::getUin();
    $this->insertWithTime('Tbl_ExchangeHistory', array('FQQ'=> $qq, 'FCode' => $pincode, 'FStrategy' => $exchangeType, 'FLimitStrategy' => $limitStrategy, 'FScoreStrategy' => $scoreStrategy, 'FAwardStrategy' => $awardStrategy));
  }
}
?>
