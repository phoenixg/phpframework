<?php
class TMScoreService
{
  public static function getUserScore($qq)
  {
    $dao = new TMUserDao;
    return $dao->getScore($qq);
  }
  public static function getuserlimit($qq,$FDate,$tablename)
  {
    $dao = new TMUserDao;
    return $dao->getUserlimit($qq,$FDate,$tablename);
  }

  public static function getUserHighest($qq)
  {
    $dao = new TMUserDao;
    return $dao->getHighest($qq);
  }

  public static function modifyUserScore($qq, $score, $strategy = 'normal', $memo = '')
  {
    if ($score < 0)
    {
      if (self::getUserScore($qq) < -$score)
      {
      }
        throw new nbMessageException('用户没有足够的积分');
    }

    $dao = new TMUserDao;
    $dao->modifyScore($qq, $score);

    $dao = new TMScoreDao;
    $dao->insertScoreHistory($qq, $score, $memo);
  }

  public static function dealStrategy($strategyOrScore, $qq = '')
  {
    if (!$strategyOrScore)
    {
      return true;
    }

    $qq = $qq ? $qq : TMAuthUtils::getUin();
    if (is_numeric($strategyOrScore))
    {
      $strategy['score'] = $strategyOrScore;
      $strategyName = '';
    }
    else
    {
      $strategies = nbAppHelper::getCurrentAppConfig('scoreStrategy', __FILE__);
      $strategyName = $strategyOrScore;

      if (!isset($strategies[$strategyName]))
      {
        throw new nbAddonException('do not have a score strategy called '.$strategyName);
      }
      else
      {
        $strategy = $strategies[$strategyName];
      }
    }

    if (isset($strategy['limitStrategy']))
    {
      $limit = new TMLimitService();
      if (!$limit->dealStrategy($strategy['limitStrategy'], 'Tbl_ScoreHistory', $strategyName))
      {
        return;
      }
    }

    if (isset($strategy['score']))
    {
      if ($strategy['score'] < 0)
      {
        if (self::getUserScore($qq) < -$strategy['score'])
        {
          throw new nbMessageException(nbHelper::getConfig('minisite/messageNoEnoughScore'));
        }
      }

      $dao = new TMScoreDao();
      $dao->insertScoreHistory($qq, $strategy['score'], $strategyName);

      if (nbAppHelper::getCurrentAppConfig('needCount', __FILE__))
      {
        $dao = new TMUserDao;
        $dao->modifyScore($qq, $strategy['score']);

      }

      if (nbAppHelper::getCurrentAppConfig('needTotalScoreCount', __FILE__) && $strategy['score'] > 0)
      {
        $dao->modifyTotalScore($qq, $strategy['score']);
      }
    }

    if (isset($strategy['alert']) && $strategy['alert'])
    {
      TMAlertHelper::set($strategy['alert']);
    }
  }
}