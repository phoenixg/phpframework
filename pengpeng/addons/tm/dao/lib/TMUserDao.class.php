<?php
class TMUserDao extends TMDao
{
  public function getUserInviteStatus($qq)
  {
    return $this->selectOne('FInviteStatus', 'Tbl_User', array('FQQ' => $qq));
  }

  public function setUserInviteStatus($qq, $status = 1)
  {
    return $this->update('Tbl_User', array('FInviteStatus' => $status), array('FQQ' => $qq));
  }

  public function getScore($qq)
  {
    return $this->selectOne('FScoreCount', 'Tbl_User', array('FQQ' => $qq));
  }
  public function getHighest($qq)
  {
    return $this->selectOne('FTotalScoreCount', 'Tbl_User', array('FQQ' => $qq));
  }

  public function modifyScore($qq, $score)
  {

    return $this->query("UPDATE Tbl_User SET FScoreCount = FScoreCount + $score WHERE FQQ = '$qq'");
  }
  public function modifyTotalScore($qq, $score)
  {

    return $this->query("UPDATE Tbl_User SET  FTotalScoreCount = FTotalScoreCount + $score WHERE FQQ = '$qq'");
  }

  public function addOneInvite($qq)
  {
    return $this->query("UPDATE Tbl_User SET FInviteCount = FInviteCount + 1 WHERE FQQ = '$qq'");
  }
  public function getUserlimit($qq,$FDate,$tablename)
  {
    return count($this->select('*', $tablename, array('FQQ' => $qq,'FDate'=>$FDate)));
  }
  public function addOneVote($qq,$votecounts)
  {
    return $this->query("UPDATE Tbl_User SET FVoteCount = FVoteCount + '$votecounts' WHERE FQQ = '$qq'");
  }

  public function updateVoteCount($qq, $count)
  {
    $this->query('UPDATE Tbl_User set FVoteCount = FVoteCount + '.$count." WHERE FQQ = '$qq'");
  }

  public function getUserInfo($qq)
  {
    return $this->selectOne('*', 'Tbl_User', array('FQQ' => $qq));
  }
}