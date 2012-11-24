<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * The class of handling socket
 *
 * @package lib.remote
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMSocket.class.php 2009-7-30 by ianzhang
 */
class TMSocket
{

    /**
     * @var resource    网络套接字
     *
     * @access private
     */
    private $socket;

    /**
     * __construct
     * 构造函数
     *
     * @return void
     */
    public function __construct()
    {
        $this->socket =  socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    /**
     * 让网络套接字连接
     *
     * @access  public
     * @param  string $ip     IP address
     * @param  string $port  IP port
     * @throws TMRemoteException
     */
    public function connect($ip,$port)
    {
        if(!socket_connect($this->socket,$ip,$port))
        {
            throw new TMRemoteException("Create connection error");
        }
    }

    /**
     * 从网络套接字发送数据
     *
     * @access  public
     * @param  string $data     发送的字符串数据
     * @return string $result   返回的字符串数据
     * @param TMRemoteException
     */
    public function sendData($data)
    {
        if(!socket_write($this->socket, $data))
        {
            throw new TMRemoteException("Send data error");
        }

        $result = "";

        do
        {
            $buffer = socket_read($this->socket, 1024);
            if($buffer == false)
            {
                break;
            }
            $size = sizeof($buffer);
            $result .= $buffer;
        }
        while($size == 1024);

        return $result;
    }

    /**
     * 关闭网络套接字
     *
     * @access public
     */
    public function close()
    {
        socket_close($this->socket);
    }
}