<?php
class TMTrackDao extends TMDao
{
  public function insertActionTrack($qq, $actionId, $tamsId)
  {
    $this->insertWithTime('Fake_Tbl_ActionTrack', array('FQQ' => $qq, 'FActionId' => $actionId, 'FTamsId' => $tamsId));
  }
}
?>
