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
 * The general Exception class of Tecent AD Platform
 * @package lib.exception
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class TMException extends Exception
{
    public function __construct($message='', $code=TMConfigConstant::EXCEPTION_DEFAULT_CODE)
    {
        parent::__construct ($message, $code);
    }

    /**
     * 异常处理，输出显示给用户
     */
    public function handle()
    {
        $this->output();
    }

    /**
     * 输出异常页面
     *
     * @param string $tpl 模板文件在templates中的相对路径
     * @param string $message 显示给用户的异常信息
     */
    public function output($tpl='', $message='')
    {
        if (empty($tpl))
        {
            $tpl = 'error/default.php';
        }
        if (empty($message))
        {
            $message = $this->getMessage();
        }
        $view = new TMView();
        $data = array(
            "autoRidrect"   => (empty($_SERVER['SERVER_TYPE']) || $_SERVER['SERVER_TYPE'] != 'test') ? true : false,
            "errorMsg"  => $message
            );
        echo $view->renderFile($data, ROOT_PATH . TMConfig::getConfig('path', 'templates') . $tpl);
        exit;
    }
}