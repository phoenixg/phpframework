<?php
class TMVoteDao extends TMDao
{

  public function insertVoteHistory($fileId, $count, $strategy, $limitStrategy, $awardStrategy, $scoreStrategy)
  {
    return $this->insertWithTime('Tbl_VoteHistory', array('FStrategy'=>$strategy,
                                                          'FLimitStrategy' => $limitStrategy,
                                                          'FAwardStrategy' => $awardStrategy,
                                                          'FScoreStrategy' => $scoreStrategy,
                                                          'FIp' => TMUtil::getClientIp(),
                                                          'FFileId' => $fileId,
                                                          'FQQ' => TMAuthUtils::getUin(),
                                                          'FVoteCounts' => $count));
  }

  public function addImage($qq, $url, $type, $memo = '')
  {
    return $this->insertWithTime('Tbl_File', array('FQQ' => $qq, 'FType' => $type, 'FUrl' => $url,'FEnable' => 1,'FMemo' => $memo));
  }

  public function updateImage($qq, $url, $type, $memo = '')
  {
    return $this->query("UPDATE Tbl_File SET FUrl = '$url', FEnable = 1 WHERE FType = '$type' AND FQQ = '$qq'");
  }

  public function updateImageCount($pid,$votecounts)
  {
    return $this->query("UPDATE Tbl_File SET FVoteCount = FVoteCount + '$votecounts' WHERE FFileId = '$pid'");
  }

  public function updateUserInfo($name,$addr,$tel,$email,$career,$company,$qq)
  {
    return $this->query("UPDATE Tbl_User SET FTrueName = '$name' , FAddr = '$addr' , FTel= '$tel' , FEmail = '$email' , FCareer = '$career' , FCollege = '$company'   WHERE FQQ = '$qq'");
  }

  public function updateHistory($qq,$history)
  {
     return $this->query("UPDATE Tbl_User SET FPersonalDesc = '$history'  WHERE FQQ = '$qq'");
  }

  public function getUserInfo($qq)
  {
     return $this->selectOne('*', 'Tbl_User', array('FQQ' => $qq));
  }
  public function getUserQQ($pid)
  {
     return $this->selectOne('FQQ', 'Tbl_File', array('FFileId' => $pid));
  }

  public function getImageState($pid)
  {
     return $this->selectOne('FEnable', 'Tbl_File', array('FFileId' => $pid));
  }
}

?>
