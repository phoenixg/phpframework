<?php
class TMWidgetDao extends TMDao
{
  public function insertWidgetHistory($limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $qq = TMAuthUtils::getUin();
    $this->insertWithTime('Tbl_WidgetHistory', array('FQQ'=> $qq, 'FLimitStrategy' => $limitStrategy, 'FScoreStrategy' => $scoreStrategy, 'FAwardStrategy' => $awardStrategy));
  }
}
?>
