<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * 判断是否通过邀请链接过来
 *
 * @package library
 * @author  woodyli <woodyli@tencent.com>
 * @version AutoRegisterFiler.class.php 2010-04-12 by woodyli
 */
class TMAutoRegisterFiler extends nbFilter
{
  /**
   *  在Execution之前，去判断是否有登录QQ，如果有则加入adid
   *
   * @param TMFilterChain $filterChain
   */
  public function execute($filterChain)
  {
    if (TMAuthUtils::isLogin())
    {
      $qq = TMAuthUtils::getUin();
      $ts = new TMService();
      if (!$ts->selectOne('FQQ', 'Tbl_User', array('FQQ' => $qq)))
      {
        $qq = TMAuthUtils::getUin();
        $myUDSInfo = TMAuthUtils::getUDSInfo($qq);
        $nickname = TMAuthUtils::getNick();
        $insertArray = array('FQQ' => $qq, 'FNick' => $nickname, 'FSex' => $myUDSInfo['gender'], 'FProvince' => $myUDSInfo['province']);

        $ts->insertWithTime($insertArray, 'Tbl_User');

        $awardStrategy = nbAppHelper::getCurrentAppConfig('awardStrategy', __FILE__);

        if ($awardStrategy)
        {
          TMAwardService::dealStrategy($awardStrategy);
        }
      }
    }

    $filterChain->execute();
  }
}