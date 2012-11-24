<?php

class TMInviteService
{

    public static function doInvite($inviterQq, $invitedQQ)
    {
        $dao = new TMInviteDao;
        if ($dao->haveInviteInfo($inviterQq, $invitedQQ))
        {
            return false;
        }
        $dao->addInviteInfo($inviterQq, $invitedQQ);
        //$dao->addInviteScore($inviterQq);


        if (nbAppHelper::getCurrentAppConfig('needCount', __FILE__))
        {
            $dao = new TMUserDao;
            $dao->addOneInvite($inviterQq);
        }

        return true;
    }

    public function CheckLimit($limit, $inviterQq, $tablename)
    {
        $FDate = date("Y-m-d");
        if (TMScoreService::getuserlimit($inviterQq, $FDate, $tablename) < $limit)
        {
            return true;
        } else
        {
            return false;
        }
    }

}