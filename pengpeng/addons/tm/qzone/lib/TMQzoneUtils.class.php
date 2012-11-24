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
 * QzoneUtils
 *
 * @package library
 * @author  ianzhang <ianzhang@tencent.com>
 * @version QzoneUtils.class.php 2010-3-4 by ianzhang
 */
class TMQzoneUtils
{
    // 是否为开发模式
    const DEBUGMODE = false;

    // 发送挂件(发送类型)
    const SEND_TYPE_W = 'W';
    // 发送日志(发送类型)
    const SEND_TYPE_B = 'B';
    // 发送Feeds(发送类型)
    const SEND_TYPE_F = 'B';

    // 在数据库中标志是否发送挂件的字段名
    const SEND_W_COLUMN = 'FQzoneWidget';
    // 在数据库中标志是否发送挂件的字段名
    const SEND_B_COLUMN = 'FQzoneBlog';
    // 在数据库中标志是否发送挂件的字段名
    const SEND_F_COLUMN = 'FQzoneFeeds';
    // 同步数据时间（挂件）
    const SYNC_TIME_W_COLUMN = 'FScoreTime';
    // 同步数据字段（挂件）
    const SYNC_W_COLUMN = 'FInviteCount';

    //已发送的标志
    const HAS_SENT = 2;
    /**
     * Socket 连接
     * @var TMSocket $socket
     */
    private $socket = null;

    /**
     * log 对象
     * @var TMLog $log
     */
    private $log = null;

    /**
     * 数据库链接类
     *
     * @var TMService $service
     */
    private $service = null;

    /**
     * 发送接口需要的配置选项
     *
     * @var array $options
     */
    private $options = array();

    /**
     * 当前用户信息
     *
     * @var array
     */
    private static $userInfo = null;

    /**
     * 和Qzone同步数据的时间点间隔
     *
     * @var unknown_type
     */
    private static $syncTime = null;

    /**
     * 将挂件成长值的变化通知qzone
     */
    public function syncWidgetData($time = '')
    {
        if (!isset(TMConstant::$qzoneSetting['widgetId'])) {
            $this->_log('Please config TMConstant::$qzoneSetting[widgetId]');
            return false;
        }
        if ($time) {
            self::$syncTime = $time;
        }
        $this->_syncData(TMConstant::$qzoneSetting['widgetId'], self::SEND_TYPE_W, self::SYNC_W_COLUMN);
    }

    /**
     * 获取有变化的用户信息
     *
     * @return array
     */
    private function _list($time = '')
    {
        if (!is_numeric($time)) {
            $time = TMConstant::$qzoneSetting['syncTime'];
        }
        $time = date("Y-m-d H:i:s", time() - $time * 60);
        $sql = "select * from Tbl_User where FQzoneWidget=" . self::HAS_SENT . " and FScoreTime>'$time'";
        $ur = $this->service->query($sql, MYSQLI_ASSOC);
        if ($ur) {
            return $ur;
        }
        return array();
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $goodId
     * @param unknown_type $type
     * @param unknown_type $qq
     * @param unknown_type $num
     */
    private function _syncData($goodId, $type, $syncColumn)
    {
        //初始化连接
        $this->_connect();
        //成长值发生变化的用户列表
        $result = $this->_list(self::$syncTime);

        $this->_log("update qzone record count: " . count($result));
        // 拼写同步描述
        if (self::SEND_TYPE_B == $type) {
            $desc = TMConfig::NAME_SPACE . ':sync blog';
        } else if (self::SEND_TYPE_W == $type) {
            $desc = TMConfig::NAME_SPACE . ':sync widget';
        } else if (self::SEND_TYPE_F == $type) {
            $desc = TMConfig::NAME_SPACE . ':sync feeds';
        }
        //同步
        foreach($result as $user) {
            $this->_sync($goodId, $user['FQQ'], $user[$syncColumn], $desc);
            //$this->updateSyncTimeForW($user['FQQ']);
        }
        //关闭连接
        $this->_close();
    }

    /**
     * 建立socket链接
     */
    private function _connect()
    {
        if ($this->socket == null)
        {
            $this->socket = new TMSocket();
            $ips = TMConstant::$qzoneSetting['syncIp'];
            if (isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == 'test')
            {
                $ip = $ips['test'];
            } else {
                $ip = $ips['online'];
            }
            $port = (int) TMConstant::$qzoneSetting['syncPort'];
            $this->socket->connect($ip, $port);
        }
    }

    /**
     * 发送同步数据
     *
     * @param int $actId 挂件活动id
     * @param string $qq 用户QQ号码
     * @param int $num 挂件成长值
     * @param string $desc
     */
    public function _sync($actId, $qq, $num, $desc='')
    {
        //如果成长值为0，不能同步
        if ($num == 0)
        {
            return ;
        }
        $format = "id=%d&uin=10000&player=%s&field=sort0&votenum=%s&type=vote&desc=%s&cmd=set&ip=0.0.0.0\n";
        $data = sprintf($format, $actId, $qq, $num, $desc);

        $result = $this->socket->sendData($data);
        $resultArray = TMUtil::handleQueryString($result);

        if ($resultArray["result"] != 0)
        {
            $this->_log("code: " . $resultArray["result"] . " msg: " . $resultArray["msg"], 'la');
        } else {
            $this->_log("success \$data: " . $data . " " . serialize($resultArray), 'la');
        }
    }

    /**
     * 关闭socket连接
     */
    private function _close()
    {
        $this->socket->close();
    }

    /**
     * 初始化建立数据库连接句柄
     *
     */
    public function __construct()
    {
        $this->service = new TMService();
    }

    private function _log($text, $type = 'lo')
    {
        $text = 'QzoneUtils: ' . $text;
        if (TMConfig::DEBUGMODE) {
            if (!$this->log) {
                $this->log = new TMLog(TMConstant::$qzoneSetting['logpath']);
            }
            $this->log->$type($text);
        }
        if (self::DEBUGMODE) {
            echo $text;
            die();
        }
    }

    /**
     * 发送Qzone挂件
     * @return int 0：失败，1：成功，2：已经发送过了
     */
    public function sendBlog($options = array())
    {
      $options['post_blog'] = 1;
      $this->uin = TMAuthUtils::getUin();
      if (!$this->service->selectOne('FQzoneBlog', 'Tbl_User', array('FQQ' => $this->uin)))
      {
        $goodId = nbAppHelper::getCurrentAppConfig('blogId', __FILE__);
        if ($_SERVER['SERVER_TYPE'] == "production")
        {
          $result = TMSendUtils::sendGuajian($goodId, $this->uin, $options, array(), "/user_v3/freereg.php");
        }
        else
        {
          $result = $this->service->insertWithTime(array('FQQ' => $this->uin, 'FGoodId' => $goodId), 'Fake_Qzone_Good');
        }

        if ($result)
        {
          $this->service->update('Tbl_User', array('FQzoneBlog' => 1), array('FQQ' => $this->uin));
          return 1;
        }
        else
        {
          return 0;
        }
      }
      else
      {
        return 2;
      }
    }

    /**
     * 发送Qzone挂件
     * @return int 0：成功，1：失败，2：已经发送过了
     */
    public function sendWidget($qq, $hang = false, $options = array())
    {
        try {
            if (!isset(TMConstant::$qzoneSetting['widgetId'])) {
                throw new LiptonException('Please config TMConstant::$qzoneSetting[widgetId]', 1);
            }
            if ($this->hasSendWidget($qq)) {
                throw new LiptonException('The widget has sent.', 2);
            }
            // 判定 hang_annex 参数的正确性
            if (!isset($options['hang_annex'])) {
                $options['hang_annex'] = $hang ? 1 : 0;
            } else if (!in_array($options['hang_annex'], array(0, 1))) {
                $options['hang_annex'] = 0;
            }
            return $this->_send(TMConstant::$qzoneSetting['widgetId'], $qq, self::SEND_TYPE_W, $options);
        } catch (LiptonException $te) {
            $this->_log($te->getMessage());
            return $te->getCode();
        } catch (TMException $te) {
            $this->_log($te->getMessage());
            return 1;
        }
    }

    /**
     * 发送QzoneFeeds
     * @return int 0：成功，1：失败，2：已经发送过了
     */
    public function sendFeeds($qq, $title, $content, $options = array())
    {
        try {
            if (!isset(TMConstant::$qzoneSetting['feedsId'])) {
                throw new LiptonException('Please config TMConstant::$qzoneSetting[feedsId]', 1);
            }
            if ($this->hasSendFeeds($qq)) {
                throw new LiptonException('The feeds has sent.', 2);
            }
            $options['fword'] = $title . " " . $content;
            $options['fword'] = str_ireplace("lt;brgt;", ' ', $options['fword']);
            $options['fword'] = TMUtil::getShortText($options['fword'], 72);
            return $this->_send(TMConstant::$qzoneSetting['feedsId'], $qq, self::SEND_TYPE_F, $options);
        } catch (LiptonException $te) {
            return $te->getCode();
        } catch (TMException $te) {
            return 1;
        }
    }

    /**
     * 手动发送Qzone虚拟物品
     *
     * @param 虚拟物品id $goodId
     * @param QQ号 $qq
     * @param 虚拟物品类型 $type
     * @param 配置选项 $option
     * @return int 0：成功，1：失败，2：已经发送过了
     */
    public function send($goodId, $qq, array $types, $option)
    {
        if (!$this->_checkSendType($types)) {
            return 1;
        }
        foreach ($types as $type) {
            if ($type == self::SEND_TYPE_B && $this->hasSendBlog($qq)) {
                return 2;
            }
            if ($type == self::SEND_TYPE_W && $this->hasSendWidget($qq)) {
                return 2;
            }
            if ($type == self::SEND_TYPE_F && $this->hasSendFeeds($qq)) {
                return 2;
            }
        }
        return $this->_send($goodId, $qq, $types, $option);
    }

    /**
     * 发送Qzone虚拟物品
     *
     * @param 虚拟物品id $goodId
     * @param QQ号 $qq
     * @param 虚拟物品类型 $type
     * @param 配置选项 $option
     * @return int 0：成功，1：失败
     */
    private function _send($goodId, $qq, $type, $option)
    {
        $r = TMSendUtils::sendGuajian($goodId, $qq, $option, array(), "/user_v3/freereg.php");
        //$r = 1;
        if ($r) {
            $this->_setQzoneStatus($qq, $type);
        }
        return $r ? 0 : 1;
    }

    /**
     * 判断用户是否已经发送日志
     *
     */
    public function hasSendBlog($qq)
    {
        return $this->_hasSend($qq, self::SEND_B_COLUMN);
    }

    /**
     * 判断用户是否已经领取挂件
     *
     */
    public function hasSendWidget($qq)
    {
        return $this->_hasSend($qq, self::SEND_W_COLUMN);
    }

    /**
     * 判断用户是否已经领取挂件
     *
     */
    public function hasSendFeeds($qq)
    {
        return $this->_hasSend($qq, self::SEND_F_COLUMN);
    }

    /**
     * 判断是否已发送相关Qzone虚拟物品
     *
     */
    private function _hasSend($qq, $type)
    {
        $userInfo = $this->_getUserInfo($qq);
        if (empty($userInfo)) {
            throw new TMException('User not exist');
        }
        if ($userInfo[$type] == self::HAS_SENT) {
            return true;
        }
        return false;
    }

    /**
     * 得到用户信息
     *
     * @param string $qq
     * @return array
     */
    private function _getUserInfo($qq)
    {
        if ((self::$userInfo === null) || (self::$userInfo['FQQ'] != $qq)) {
            self::$userInfo = $this->service->selectOne(array("FQQ" => $qq), ' * ', 'Tbl_User');
        }
        return self::$userInfo;
    }

    /**
     * 检验发送类型是否正确
     *
     * @param string|array $type
     * @return boolean true|false
     */
    private function _checkSendType($type)
    {
        $types = array(self::SEND_TYPE_B, self::SEND_TYPE_W, self::SEND_TYPE_F);
        if (is_string($type)) {
            if (in_array($type, $types)) {
                return true;
            } else {
                return false;
            }
        } else if (is_array($type)) {
            $isPass = array_diff($type, $types);
            if (empty($isPass)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }


    /**
     * 设置用户是否已经领取挂件的状态
     */
    private function _setQzoneStatus($qq, $type)
    {
        $types = is_string($type) ? array($type) : $type;
        $userInfo = $this->_getUserInfo($qq);
        $data = array();
        foreach ($types as $v) {
            eval('$colum = self::SEND_' . $v. '_COLUMN;');
            $data[$colum] = self::HAS_SENT;
            if (($colum == self::SEND_W_COLUMN) && isset($userInfo[self::SYNC_TIME_W_COLUMN])) {
                TMService::setTimeForUpdateOrInsert($data, self::SYNC_TIME_W_COLUMN);
            }
        }
        $this->service->update($data, array("FQQ" => $qq), 'Tbl_User');
    }

    /**
     * 更新用户积分更新时间
     *
     */
    public function updateSyncTimeForW($qq)
    {
        $data = array();
        TMService::setTimeForUpdateOrInsert($data, self::SYNC_TIME_W_COLUMN);
        $this->service->update($data, array("FQQ" => $qq), 'Tbl_User');
    }
}