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
 * The utility to output the debug information into log.
 * The debug setting has been configured in the TMConfig as DEBUGMODE.
 *
 * @package lib.util
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-11-21
 */
class TMDebugUtils
{
    /**
     * Output the debug information into LOG
     *
     * @param  string $message     debug Information
     */
    public static function  debugLog($message)
    {
        if (TMConfig::DEBUGMODE)
        {
            $log = new TMLog();
            $log->lo($message);
        }
    }

    /**
     * Print the debug Information on the screen
     *
     * @param  string $message     debug information
     */
    public static function debugEcho($message)
    {
        if (TMConfig::DEBUGMODE)
        {
            print($message);
        }
    }

    /**
     * Print the debug Information on the screen, and break the program.
     *
     * @param  string $message     debug information
     */
    public static function debugEchoBreak($message)
    {
        if (TMConfig::DEBUGMODE)
        {
            print($message);
            exit;
        }
    }
}