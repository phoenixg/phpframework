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
 * The Mysql exception class
 *
 * @package lib.db
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class TMMysqlException extends Exception
{
//    public function __construct($message, $code = TMConfigConstant::EXCEPTION_MYSQL_CODE)
//    {
//        parent::__construct ( $message, $code );
//        $log = new TMLog();
//        $log->la($message);
//    }
//
//    /**
//     * 异常处理，输出显示给用户
//     */
//    public function handle()
//    {
//        $log = new TMLog();
//        $log->la($this->getMessage());
//        if (empty($_SERVER['SERVER_TYPE']) || $_SERVER['SERVER_TYPE'] != 'test')
//        {
//            $message = '系统繁忙';
//        }else if(!empty($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == 'test'){
//            $message = "<pre>".$this->getTraceAsString()."</pre>";
//        }
//        $this->output('', $message);
//    }
}