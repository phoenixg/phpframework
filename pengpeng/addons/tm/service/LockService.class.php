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
 * The lock service
 *
 * @package lib.service
 * @author  samonma <samonma@tencent.com>
 * @version LockService.class.php 2009-09-24 by samonma
 */
class LockService
{
    /**
     * 在缓存管理器中设置指定的值，来做标记（上锁）
     *
     * @param string $key 标记名
     * @param integer $expire 过期时间
     * @return 缓存管理器所返回的提示信息
     */
    public static function getLock($key, $expire = 0)
    {
        try
        {
            $memCache = TMMemCacheMgr::getInstance();
            return $memCache->add($key, 'lock', $expire);
        }
        catch(TMCacheException $tce)
        {
            $log = new TMLog();
            $log->la("lock cache failed");
        }
    }

    /**
     * 清除之前在缓存管理器中设定的指定值(解锁)
     *
     * @param string $key 标记名
     */
    public static function unlock($key)
    {
        try
        {
            $memCache = TMMemCacheMgr::getInstance();
            $memCache->clear($key);
        }
        catch(TMCacheException $tce)
        {
            $log = new TMLog();
            $log->la("unlock cache failed");
        }
    }
}