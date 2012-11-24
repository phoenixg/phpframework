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

/**
 * 使用作品对其他人进行邀请
 *
 * @package package
 * @author  jasoncao <jasoncao@tencent.com>
 * @version InviteFilter.class.php 2010-08-11 by jasoncao
 */
class TMFileInviteFilter extends AMFilter {
	/*
	 *
	 */
	public function execute($filterChain) {
	  if ($inviterQq = nbRequest::getInstance()->getGet('inviterQq'))
		{
      if (!TMAuthUtils::isLogin ())
      {
        $path = nbRequest::getInstance()->getHost().'tm-page.php/user/login?url='.urlencode(nbRequest::getInstance()->getUrl());
        echo "<script>window.location.href='$path'</script>";
        exit;
      }

		  $service = new TMService();

      if (!$service->selectOne('count(*)', 'Tbl_InviteHistory', array('FInviterQQ' => $inviterQq, 'FInvitedQQ' => TMAuthUtils::getUin())))
      {
        $insertArray = array('FInviterQQ' => $inviterQq, 'FInvitedQQ' => TMAuthUtils::getUin());

        if ($inviterId = nbRequest::getInstance()->getGet('inviterId'))
        {
          if (!nbRequest::getInstance()->getGet('inviterPass'))
          {
            echo 'need pass';
            exit;
          }
          else if (nbRequest::getInstance()->getGet('inviterPass') != TMInviteTool::getInvitePass($inviterId))
          {
            echo 'need correct pass';
            exit;
          }
          else if ($this->overInviteLimit($inviterId))
          {
            echo "<script>window.location.href='".nbRequest::getInstance()->getHost()."'</script>";
            exit;
          }
          else
          {
            $insertArray['FInviterId'] = $inviterId;
          }
        }

        $service->insertWithTime($insertArray, 'Tbl_InviteHistory');
      }
		}

		$filterChain->execute();
	}

  private function overInviteLimit($inviterId)
  {
    if (nbAppHelper::getCurrentAppConfig('checkInviterLimit', __FILE__))
    {
      $service = new TMService();
      $invitedCount = $service->selectOne('count(*)', 'Tbl_InviteHistory', array('FInviterId' => $inviterId));

      return $invitedCount >= nbAppHelper::getCurrentAppConfig('inviterLimitCount', __FILE__);
    }
    else
    {
      return false;
    }
  }
}
