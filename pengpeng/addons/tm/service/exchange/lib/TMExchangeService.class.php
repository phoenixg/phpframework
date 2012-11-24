<?php
class TMExchangeService
{
  public static function insertExchangeHistory($pincode, $exchangeType, $limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $dao = new TMPincodeDao;
    $dao->insertExchangeHistory($pincode, $exchangeType, $limitStrategy, $scoreStrategy, $awardStrategy);
  }

  public static function pincodeCanBeUsed($pincode)
  {
    $dao = new TMPincodeDao;
    if (!$dao->hasPincode($pincode))
    {
      throw new nbMessageException('您输入代码错误，请重新输入');
    }

    if (!$dao->hasNoUsedPincode($pincode))
    {
      throw new nbMessageException('您输入代码已被使用，请重新输入');
    }

    return true;
  }
  public function pincodeType($pincode)
  {
     $dao = new TMPincodeDao;
     return $dao->pincodeType($pincode);
  }
  
  public static function pingcodeJustUsed($pincode)
  {
    $dao = new TMPincodeDao;
    $dao->usePincode($pincode);
  }
}
?>
