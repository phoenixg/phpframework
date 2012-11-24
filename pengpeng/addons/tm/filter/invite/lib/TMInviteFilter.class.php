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
 * InviteFilter
 * description
 *
 * @package package
 * @author  jasoncao <jasoncao@tencent.com>
 * @version InviteFilter.class.php 2010-08-11 by jasoncao
 */
class TMInviteFilter extends nbFilter
{
  /*
   * 完成登录即注册、邀请好友记录功能
   */
  private $doNext = true;

  public function execute($filterChain)
  {
    $inviterQq = nbRequest::getInstance()->getGet('fromqq');

    if ($inviterQq)
    {
      if (!TMAuthUtils::isLogin())
      {
        $path = nbRequest::getInstance()->getHost().'tm-page.php/user/login?url='.urlencode(nbRequest::getInstance()->getUrl());
        nbMvcHelper::redirect($path);
      }

      $uin = TMAuthUtils::getUin();

      $service = new TMService();
      if (!$service->selectOne('count(*)', 'Tbl_InviteHistory', array('FInvitedQQ' => $uin)) && $uin != $inviterQq)
      {
        TMHelper::actionTrack(6000117, $inviterQq);
        $strategyName = nbAppHelper::getCurrentAppConfig('strategyName', __FILE__);

        $limitResult = true;
        if (isset($strategyName['limitStrategy']))
        {
          $limit = new TMLimitService();
          $limitResult = $limit->dealStrategy($strategyName['limitStrategy'], 'Tbl_InviteHistory','',$inviterQq);
        }

        if ($limitResult)
        {
          if (isset($strategyName['awardStrategy']))
          {
            TMAwardService::dealStrategy($strategyName['awardStrategy'], $inviterQq);
          }
        }

        if (isset($strategyName['scoreStrategy']))
        {
          TMScoreService::dealStrategy($strategyName['scoreStrategy'], $inviterQq);
        }

        TMInviteService::doInvite($inviterQq, $uin);
      }
    }

    $filterChain->execute();
  }
}
