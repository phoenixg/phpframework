<?php

class TMLimitService
{

    public function dealStrategy($limitStrategyName, $tableName, $strategyName = '', $qq = '')
    {
        if (!$limitStrategyName)
        {
            return true;
        }

        $this->qq = $qq ? $qq : TMAuthUtils::getUin();
        $strategies = nbAppHelper::getCurrentAppConfig('limitStrategy', __FILE__);

        if (!isset($strategies[$limitStrategyName]))
        {
            throw new nbAddonException('do not have a limit strategy called ' . $limitStrategyName);
        }
        else
        {
            $strategy = $strategies[$limitStrategyName];
        }

        if (!isset($strategy['message']))
        {
            $strategy['message'] = array();
        }

        if (isset($strategy['qqToAnotherLimit']))
        {
            if (in_array($this->qq, $strategy['qqToAnotherLimit']['qq']))
            {
                return $this->dealStrategy($strategy['qqToAnotherLimit']['limitStrategy'], $tableName, $strategyName, $this->qq);
            }
        }

        try
        {
            $this->limitCheck($strategy['limit'], $strategy['message'], $tableName, $strategyName);
            return true;
        }
        catch (nbGotoException $e)
        {
            return false;
        }
    }

    private function limitCheck($limit, $limitMessage, $table, $strategyName)
    {
        $this->limit = $limit;
        $this->limitMessage = $limitMessage;
        $this->table = $table;
        $this->strategyName = $strategyName;
        $this->service = new TMService;

        if ($strategyName)
        {
            $baseCondition['FStrategy'] = $strategyName;
        }
        else
        {
            $baseCondition = array();
        }

        if (isset($this->limit['user']))
        {
            $this->passUserDayLimit($baseCondition);
            //$this->passLimit('user', 'week', $condition); // to do
            //$this->passLimit('user', 'month', $condition); // to do
            $this->passUserTotalLimit($baseCondition);
            $this->passUserZoneLimit($baseCondition);
            $this->passUserHourLimit($baseCondition);
        }

        if (isset($this->limit['item']))
        {
            $this->passItemDayLimit($baseCondition);
            $this->passItemTotalLimit($baseCondition);
            $this->passItemZoneLimit($baseCondition);
            $this->passItemHourLimit($baseCondition);
        }

        if (isset($this->limit['score']))
        {
            $this->passScoreLimit('score', $this->limit['score']);
        }

        if (isset($this->limit['highestScore']))
        {
            $this->passHighestScoreLimit('highestScore', $this->limit['highestScore']);
        }
    }

    private function passUserDayLimit($condition)
    {
        if (isset($this->limit['user']['day']))
        {
            $condition['FDate'] = date('Y-m-d');
            if (nbHelper::getConfig('tm-service-invite/limitColumn'))
            {
                $limitCoiumn = nbHelper::getConfig('tm-service-invite/limitColumn');
                $condition[$limitCoiumn] = $this->qq;
            }
            else
            {
                $condition['FQQ'] = $this->qq;
            }


            $this->passLimit($condition, 'user', 'day');
        }
    }

    private function passUserTotalLimit($condition)
    {
        if (isset($this->limit['user']['total']))
        {
            if (nbHelper::getConfig('tm-service-invite/limitColumn'))
            {
                $limitCoiumn = nbHelper::getConfig('tm-service-invite/limitColumn');
                $condition[$limitCoiumn] = $this->qq;
            }
            else
            {
                $condition['FQQ'] = $this->qq;
            }

            $this->passLimit($condition, 'user', 'total');
        }
    }

    private function passUserZoneLimit($condition)
    {
        if (isset($this->limit['user']['zone']))
        {
            $zoneName = $this->getCurrentZoneName();
            $zoneList = nbAppHelper::getCurrentAppConfig('zoneList', __FILE__);

            if (nbHelper::getConfig('tm-service-invite/limitColumn'))
            {
                $limitCoiumn = nbHelper::getConfig('tm-service-invite/limitColumn');
                $condition[$limitCoiumn] = $this->qq;
            }
            else
            {
                $condition['FQQ'] = $this->qq;
            }
            $condition['FDate|>='] = $zoneList[$zoneName]['start'];
            $condition['FDate|<'] = $zoneList[$zoneName]['end'];

            $this->passLimit($condition, 'user', 'zone');
        }
    }

    private function passUserHourLimit($condition)
    {
        if (isset($this->limit['user']['hour']))
        {
            //$hourName = $this->getCurrentHourName();
            //$hourList = nbAppHelper::getCurrentAppConfig('hourList', __FILE__);

            if (nbHelper::getConfig('tm-service-invite/limitColumn'))
            {
                $limitCoiumn = nbHelper::getConfig('tm-service-invite/limitColumn');
                $condition[$limitCoiumn] = $this->qq;
            }
            else
            {
                $condition['FQQ'] = $this->qq;
            }
            $condition['FTime|>='] = date("Y-m-d H") . ":00:00";
            $condition['FTime|<'] = date("Y-m-d H") . ":59:59";

            $this->passLimit($condition, 'user', 'hour');
        }
    }

    private function passItemDayLimit($condition)
    {
        if (isset($this->limit['item']['day']))
        {
            $condition['FDate'] = date('Y-m-d');

            $this->passLimit($condition, 'item', 'day');
        }
    }

    private function passItemTotalLimit($condition)
    {
        if (isset($this->limit['item']['total']))
        {
            $this->passLimit($condition, 'item', 'total');
        }
    }

    private function passItemZoneLimit($condition)
    {
        if (isset($this->limit['item']['zone']))
        {
            $zoneName = $this->getCurrentZoneName();
            $zoneList = nbAppHelper::getCurrentAppConfig('zoneList', __FILE__);

            $condition['FDate|>='] = $zoneList[$zoneName]['start'];
            $condition['FDate|<'] = $zoneList[$zoneName]['end'];

            $this->passLimit($condition, 'item', 'zone');
        }
    }

    private function passItemHourLimit($condition)
    {
        if (isset($this->limit['item']['hour']))
        {
            //$hourName = $this->getCurrentHourName();
            $condition['FTime|>='] = date("Y-m-d H") . ":00:00";
            $condition['FTime|<'] = date("Y-m-d H") . ":59:59";
            $this->passLimit($condition, 'item', 'hour');
        }
    }

    private function passScoreLimit($limitType, $score)
    {
        if (isset($this->limit['score']))
        {
            $userScore = $this->service->selectOne('FScoreCount', 'Tbl_User', array('FQQ' => $this->qq));

            if ($userScore < $score)
            {
                if ($this->limitMessage && isset($this->limitMessage[$limitType]))
                {
                    throw new nbMessageException($this->limitMessage[$limitType]);
                }
                else
                {
                    throw new nbMessageException('do not pass the limit!');
                }
            }
        }
    }

    private function passHighestScoreLimit($limitType, $score)
    {
        if (isset($this->limit['highestScore']))
        {
            $userScore = $this->service->selectOne('FTotalScoreCount', 'Tbl_User', array('FQQ' => $this->qq));
            if ($userScore < $score)
            {
                if ($this->limitMessage && isset($this->limitMessage[$limitType]))
                {
                    throw new nbMessageException($this->limitMessage[$limitType]);
                }
                else
                {
                    throw new nbMessageException('do not pass the limit!');
                }
            }
        }
    }

    private function getCurrentZoneName()
    {
        $zoneList = nbAppHelper::getCurrentAppConfig('zoneList', __FILE__);

        foreach ($zoneList as $zoneName => $zoneTime)
        {
            $time = date('Y-m-d H:i:s');
            if ($time < $zoneTime['end'] && $time > $zoneTime['start'])
            {
                return $zoneName;
            }
        }

        throw new nbAddonException('do not in any zone of this project config!');
    }

    private function passLimit($condition, $zoneType, $timeType)
    {
        if (isset($this->limit[$zoneType][$timeType]))
        {
            $total = $this->service->selectOne('count(*)', $this->table, $condition);
            if ($total >= $this->limit[$zoneType][$timeType])
            {
                if ($this->limitMessage && isset($this->limit[$zoneType]) && isset($this->limit[$zoneType][$timeType]))
                {
                    if ($this->limitMessage[$zoneType][$timeType] == 4 || $this->limitMessage[$zoneType][$timeType] == 2)
                    {
                        $this->returnJson(array("message" => $this->limitMessage[$zoneType][$timeType]));
                    }
                    else
                    {
                        throw new nbGotoException();
                    }
                }
                else
                {
                    throw new nbGotoException();
                }
            }
        }
    }

    public function returnJson(array $message)
    {
        echo json_encode($message);
        exit;
    }

}

?>
