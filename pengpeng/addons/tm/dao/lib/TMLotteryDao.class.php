<?php
class TMLotteryDao extends TMDao
{
  public function insertLotteryHistory($position, $limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $qq = TMAuthUtils::getUin();
    $this->insertWithTime('Tbl_LotteryHistory', array('FQQ'=> $qq, 'FPosition' => $position, 'FLimitStrategy' => $limitStrategy, 'FScoreStrategy' => $scoreStrategy, 'FAwardStrategy' => $awardStrategy));
  }
}
?>
