<?php

class TMAwardService
{

    /**
     * 第三个参数是当多个不同的奖品策略，需要使用同一种限制策略是，就手动调整为相同的策略名
     */
    public function dealStrategy($awardStrategyName, $qq = '', $strategyName = '')
    {
        if (!$strategyName)
        {
            $strategyName = $awardStrategyName;
        }

        $qq = $qq ? $qq : TMAuthUtils::getUin();

        $strategies = nbAppHelper::getCurrentAppConfig('awardStrategy', __FILE__);

        if (!isset($strategies[$awardStrategyName]))
        {
            throw new nbAddonException('do not have a award strategy called ' . $awardStrategyName);
        }

        $strategy = $strategies[$awardStrategyName];

        $passLimit = true;
        if (isset($strategy['limitStrategy']))
        {
            $limit = new TMLimitService;
            $passLimit = $limit->dealStrategy($strategy['limitStrategy'], 'Tbl_Award', $strategyName, $qq);
        }

        if ($passLimit)
        {
            self::dealPMAward($qq, $strategy, $strategyName);
            self::dealScoreAward($qq, $strategy, $strategyName);
            self::dealOtherAward($qq, $strategy, $strategyName);
        }
    }

    public function dealGroupStrategy($groupName, $strategyName, $qq = '')
    {
        $qq = $qq ? $qq : TMAuthUtils::getUin();

        $allStrategies = nbAppHelper::getCurrentAppConfig('awardStrategy', __FILE__);
        if (!isset($allStrategies[$groupName]))
        {
            throw new nbAddonException('do not have a group award strategy called ' . $groupName);
        }

        $strategies = $allStrategies[$groupName];

        if (isset($strategies['limitStrategy']))
        {
            $limit = new TMLimitService;
            $limit->dealStrategy($strategies['limitStrategy'], 'Tbl_Award', $groupName);
        }

        if (!isset($strategies[$strategyName]))
        {
            throw new nbAddonException('do not have a award strategy called ' . $strategyName);
        }

        $strategy = $strategies[$strategyName];

        self::dealPMAward($qq, $strategy, $groupName);
        self::dealScoreAward($qq, $strategy, $groupName);
        self::dealOtherAward($qq, $strategy, $groupName);
    }

    private static function dealPMAward($qq, $strategy, $strategyName)
    {
        if (!isset($strategy['mpAward']))
        {
            return;
        }

        $sendResult = false;

        if (isset($strategy['mpAward']['sendByTime']) && $strategy['mpAward']['sendByTime'])
        {
            if ($_SERVER['SERVER_TYPE'] == "dev" || $_SERVER['SERVER_TYPE'] == "localhost")
            {
                $dao = new TMAwardDao();
                $sendResult = $dao->sendFakeAward($qq, $strategy['mpAward']['productNo'], $strategy['mpAward']['itemNo']);
            }
            else
            {
                $proname = nbHelper::getConfig('minisite/nameSpace');
                $proid = $strategy['mpAward']['productNo'];
                $proitem = $strategy['mpAward']['itemNo'];

                new nbLog("$proname, $proid, $proitem, $qq");
                $url = "http://emarketing.qq.com/cgi-bin/em_sendqqshow";
                if (isset($_SERVER['SERVER_TYPE']) && $_SERVER['SERVER_TYPE'] == "test")
                {
                    $url .= "_test";
                }
                $service = new TMCurl($url);
                $key = md5($proname . "*" . $qq);
                $result = $service->sendByGet(array('proname' => $proname, 'proid' => $proid, 'proitem' => $proitem, 'qq' => $qq, 'propwd' => $key));
                if ($result == "result=0")
                {
                    $sendResult = 2;
                }
                else
                {
                    $sendResult = 1;
                }

                //$sendResult = TMSendUtils::sendQQShow(nbHelper::getConfig('minisite/nameSpace'), $strategy['mpAward']['productNo'], $strategy['mpAward']['itemNo'], $qq);
            }
        }

        $dao = new TMAwardDao();
        if (isset($strategy['mpAward']))
        {
            $awardZone = '';
            $dao->insertPMAward($qq, $strategyName, $awardZone, $strategy['mpAward']['productNo'], $strategy['mpAward']['itemNo'], $sendResult);
        }
        else
        {
            $dao->insertAward($qq, $strategyName);
        }
    }

    private static function dealScoreAward($qq, $strategy, $strategyName)
    {
        if (!isset($strategy['scoreStrategy']))
        {
            return;
        }

        $strategyName = $strategy['scoreStrategy'];
        TMScoreService::dealStrategy($strategyName);

        $dao = new TMAwardDao();
        $dao->insertAward($qq, $strategyName);
    }

    private static function dealOtherAward($qq, $strategy, $strategyName)
    {
        if (!isset($strategy['otherAward']))
        {
            return;
        }

        $dao = new TMAwardDao();
        $dao->insertAward($qq, $strategyName);
    }

}