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
class TMIpFilter extends AMFilter
{
  public function execute($filterChain)
  {
    if ($this->beforeTimestamp() && $this->checkModuleAndAction())
    {
      $this->checkIp();
    }

    $filterChain->execute();
  }

  private function beforeTimestamp()
  {
    if (!$timestamp = CoreConfigTool::getConfig('tm-filter-ip/timestamp'))
    {
      $timestamp = CoreConfigTool::getConfig('minisite/start');
    }

    return date('Y-m-d H:i:s') < $timestamp;
  }

  private function checkModuleAndAction()
  {
    if (in_array('*', CoreConfigTool::getConfig('tm-filter-ip/module')))
    {
      return true;
    }

    $moduleName = nbRequest::getInstance()->getModuleName();
    if (in_array($moduleName, CoreConfigTool::getConfig('tm-filter-ip/module')))
    {
      if (in_array('*', CoreConfigTool::getConfig('tm-filter-ip/action')))
      {
        return true;
      }

      $actionName = nbRequest::getInstance()->getActionName();
      if (in_array($actionName, CoreConfigTool::getConfig('tm-filter-ip/action')))
      {
        return true;
      }
    }

    return false;
  }

  private function checkIp()
  {
    $userIp = TMUtil::getClientIp();

    if (!in_array($userIp, (array)CoreConfigTool::getConfig('tm-filter-ip/allowIps')))
    {
      echo "Your ip ($userIp) is not allowed to access this page.";
      exit;
    }
  }
}