<?php
class TMLotteryService
{
  public static function insertLotteryHistory($position, $limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $dao = new TMLotteryDao;
    $dao->insertLotteryHistory($position, $limitStrategy, $scoreStrategy, $awardStrategy);
  }
}
?>
