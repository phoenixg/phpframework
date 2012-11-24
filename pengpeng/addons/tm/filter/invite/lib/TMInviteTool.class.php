<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

class TMInviteTool
{
    public static function linkToInvite($needJump = true)
    {
      $uin = TMAuthUtils::getUin();
      $url = nbRequest::getInstance()->getHost().'?fromqq='.$uin;
      
      if ($needJump)
      {
        $url = TMHelper::urlJump($url, 1);
        
      }

      return $url;
    }

    public static function linkToFileInvite($id, $qq = '')
    {
      $qq = $qq ? $qq : TMAuthUtils::getUin();

      $inviterPass = self::getInvitePass($id);
      return nbRequest::getInstance()->getHost().'?fromqq='.$qq.'&inviterId='.$id.'&inviterPass='.$inviterPass;
    }

    public static function getInvitePass($qq)
    {
      return md5(nbHelper::getConfig('minisite/nameSpace').$qq);
    }
}
