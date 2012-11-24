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
 * 判断用户登录是否发送登录行为监测
 *
 *
 * @package library
 * @author  用户登录是否发送登录行为监测 <@tencent.com>
 * @version TMTrackLoginFilter.class.php 2010-11-10 by
 */
class TMTrackLoginFilter extends nbFilter
{
  /**
   *  用户登录是否发送登录行为监测
   *
   * @param TMFilterChain $filterChain
   */
  public function execute($filterChain)
  {
    try
    {
      if (TMAuthUtils::isLogin())
      {
        $this->trackLogin(TMAuthUtils::getUin());
      }

    }
    catch(Exception $te)
    {

    }

    $filterChain->execute();
  }

  /**
   * 监测登录次数
   * @param $uin
   */
  private function trackLogin($uin)
  {
    $hasTrack = isset($_COOKIE['track_login']) ? $_COOKIE['track_login'] : 0;

    if (!$hasTrack)
    {
      TMHelper::actionTrack(6000101);
      setcookie('track_login', 1, 0, "/", $_SERVER['HTTP_HOST']);
    }
  }
}