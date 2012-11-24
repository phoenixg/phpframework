<?php

class TMVoteService
{
  public static function doVote($fileId, $count, $strategy, $limitStrategy, $awardStrategy, $scoreStrategy)
  {
    $dao = new TMVoteDao;
    $dao->insertVoteHistory($fileId, $count, $strategy, $limitStrategy, $awardStrategy, $scoreStrategy);

    $dao = new TMFileDao;
    $dao->updateVoteCount($fileId, $count);
    $qq = $dao->getFqqById($fileId);

    $dao = new TMUserDao;
    $dao->updateVoteCount($qq, $count);
  }

  public static function updateUserInfo($name,$addr,$tel,$email,$career,$company,$qq)
  {
    $dao = new TMVoteDao;
    $dao->updateUserInfo($name,$addr,$tel,$email,$career,$company,$qq);
  }

  public static function updateHistory($qq,$history)
  {
    $dao = new TMVoteDao;
    $dao->updateHistory($qq,$history);
  }

  public static function insertVoteHistory($FStrategy, $qq, $votecounts,$pid, $memo = '')
  {
    $dao = new TMVoteDao;
    $dao->insertVoteHistory($FStrategy,$qq, $votecounts,$pid, $memo = '');
  }

  public static function addImage($qq, $url, $type, $memo = '')
  {
    $dao = new TMVoteDao;
    return $dao->addImage($qq, $url, $type, $memo = '');
  }

  public static function updateImage($qq, $url, $type, $memo = '')
  {
    $dao = new TMVoteDao;
    return $dao->updateImage($qq, $url, $type, $memo = '');
  }

  public static function updateImageCount($pid,$votecounts)
  {
     $dao = new TMVoteDao;
     $dao->updateImageCount($pid,$votecounts);
  }

  public static function addOneVote($qq,$votecounts)
  {
    $dao = new TMUserDao;
    $dao->addOneVote($qq,$votecounts);
  }

  public static function getUserInfo($qq)
  {
     $dao = new TMVoteDao;
     $dao->getUserInfo($qq);
  }
  public static function getUserQQ($pid)
  {
    $dao = new TMVoteDao;
    return $dao->getUserQQ($pid);
  }
  public function getImageState($pid)
  {
    $dao = new TMVoteDao;
    return $dao->getImageState($pid);
  }

}

?>
