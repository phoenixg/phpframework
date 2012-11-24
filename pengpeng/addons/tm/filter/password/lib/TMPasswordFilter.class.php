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
 * 目前而言主要是指是否需要对网站进行访问ip控制
 * 如果开启了此控制，则只有在config/validated_ip.yml名单中的ip才有权限访问
 * 开关：config/filter.yml - TMSecurityFilter
 *
 * @package lib.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMSecurityFilter.class.php 2009-4-16 by ianzhang
 * @version TMSecurityFilter.class.php 2010-1-21 by ryanfu
 */
class TMPasswordFilter extends nbFilter
{
  public function execute($filterChain)
  {
    if ($this->beforeTimestamp() && !$this->inAllowIps())
    {
      if (isset($_COOKIE['tm_filter_password']) && $_COOKIE['tm_filter_password'] == 1)
      {
        $filterChain->execute();
      }
      else
      {
        $path = nbRequest::getInstance()->getHost().'tm-filter-password.php?url='.urlencode(nbRequest::getInstance()->getUrl());
        echo "<script>window.location.href='$path'</script>";
        exit;
      }
    }
    else
    {
      $filterChain->execute();
    }
  }

  private function beforeTimestamp()
  {
    $timestamp = nbHelper::getConfig('minisite/start');

    return date('Y-m-d H:i:s') < $timestamp;
  }

  private function inAllowIps()
  {
    return in_array(TMUtil::getClientIp(), nbHelper::getConfig('minisite/allowIps'));
  }
}