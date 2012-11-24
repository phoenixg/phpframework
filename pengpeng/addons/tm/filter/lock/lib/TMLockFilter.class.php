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
 * 流程链--安全控制类
 *
 * LIB库内部调用
 * 目前而言主要是指是否需要对网站进行防止并发控制
 * 开关：config/filter.yml - TMLockFilter
 *
 * @package lib.filter
 * @author  samonma <samonma@tencent.com>
 * @version TMLockFilter.class.php 2009-4-16 by samonma
 */
class TMLockFilter extends nbFilter
{
  /**
   * 执行安全逻辑
   * 目前而言指增加ip访问控制
   *
   * @param TMFilterChain $filterChain
   */
  public function execute($filterChain)
  {
    if (nbHelper::currentInAction(nbAppHelper::getCurrentAppConfig('actionsUsed', __FILE__)))
    {
      if(TMAuthUtils::isLogin())
      {
        $qq = TMAuthUtils::getUin();
        if(!LockService::getLock(nbHelper::getConfig('minisite/appid').'_'.$qq.'_lockFilter', nbAppHelper::getCurrentAppConfig('lockTime', __FILE__)))
        {
          throw new nbAddonException('连接过多！');
          exit;
        }
      }
    }

    $filterChain->execute();
  }
}