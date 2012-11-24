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
 * Advertisement Platform LOG
 *
 * @package lib.log
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-8
 */
class TMLog
{
    private $file;
    const statusHigh = "H";
    const statusLow = "L";
    const statusAlert = "A";

    /**
     * 构造函数
     *
     * @param string $path 日志文件的路径
     */
    public function __construct($path = '')
    {
      if (!$path)
      {
        $path = nbHelper::getConfig('minisite/logPath');
      }
        $logSize = 134217728; //128M
        $logSize = (@constant("TMConfig::ERRORLOG_MAXSIZE")=== null) ? $logSize : TMConfig::ERRORLOG_MAXSIZE;
        $filesize = @filesize($path);
        if (!empty($filesize) && $filesize > $logSize)
        {
            $lockServ = new LockService();
            if ($lockServ->lock('log_file_path', 10))
            {
                @unlink($path."_php_4");
                @rename($path."_php_3", $path."_php_4");
                @rename($path."_php_2", $path."_php_3");
                @rename($path, $path."_php_2");
                @unlink($path);
            }
        }

        try
        {
            $this->file = new TMFile($path, "ab+");
        }
        catch(TMException $te)
        {
        }
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
    }

    /**
     * lh
     * High Priority Log
     *
     * @param string $msg     the log message
     */
    public function lh($msg)
    {
        $this->formatWrite($msg,self::statusHigh);
    }

    /**
     * ll
     * Low Priority Log
     *
     * @param string $msg     the log message
     */
    public function ll($msg)
    {
        $this->formatWrite($msg,self::statusLow);
    }

    /**
     * la
     * ALERT (Emergency) Priority Log. This level is the highest. Please use it carefully
     *
     * @param string $msg     the log message
     */
    public function la($msg)
    {
        $this->formatWrite($msg,self::statusAlert);
    }

    /**
     * Normal log
     * You could use it to debug your program. Please see the class TMDebugUtils in util package
     *
     * @param string $msg     the log message
     */
    public function lo($msg)
    {
        $this->formatWrite($msg);
    }

    /**
     * formatWrite
     * 格式化输出
     *
     * @param string $msg      输出的log信息内容
     * @param string $status   输出标志位信息
     */
    private function formatWrite($msg,$status="")
    {
        $date = date("H:i:s Ymd");
        if (!empty($status))
        {
            $val = "[".$date."] <".$status.">".$msg.".\n";
        }
        else
        {
            $val = "[".$date."]".$msg.".\n";
        }
        $this->file->insert($val);
    }
}