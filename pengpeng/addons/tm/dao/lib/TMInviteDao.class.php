<?php
class TMInviteDao extends TMDao
{
  public function addInviteInfo($inviterQq, $invitedQQ)
  {
    $service = new TMService();
    $insertArray = array('FInviterQQ' => $inviterQq, 'FInvitedQQ' => $invitedQQ);
    $service->insertWithTime($insertArray, 'Tbl_InviteHistory');

    //TMUserDao::setUserInviteStatus($invitedQQ, 1);
  }
  

  public function haveInviteInfo($inviterQq, $invitedQQ)
  {
    $service = new TMService();
    if ($service->selectOne('count(*)', 'Tbl_InviteHistory', array('FInviterQQ' => $inviterQq, 'FInvitedQQ' => $invitedQQ)))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
}