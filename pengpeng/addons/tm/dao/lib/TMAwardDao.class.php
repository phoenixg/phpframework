<?php
class TMAwardDao extends TMDao
{
  public function insertAward($qq, $strategyName)
  {
    $this->insertWithTime('Tbl_Award', array('FQQ' => $qq, 'FStrategy' => $strategyName));
  }

  public function insertPMAward($qq, $strategyName, $awardZone, $productNo, $itemNo, $sendStatus = 0)
  {
    $this->insertWithTime('Tbl_Award', array('FQQ' => $qq, 'FStrategy' => $strategyName, 'FPMProductNo' => $productNo, 'FPMItemNo' => $itemNo, 'FPMSendStatus' => $sendStatus));
  }

  public function sendFakeAward($qq, $productNo, $itemNo)
  {
    return $this->insertWithTime('Fake_Send_Tbl_Award', array('FQQ' => $qq, 'FProductNo' => $productNo, 'FItemNo' => $itemNo));
  }
}