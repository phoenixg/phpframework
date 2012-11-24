<?php
class TMScoreDao extends TMDao
{
  public function insertScoreHistory($qq, $score, $strategy, $memo = '')
  {
    return $this->insertWithTime('Tbl_ScoreHistory', array('FQQ' => $qq, 'FScore' => $score, 'FIp' => TMUtil::getClientIp(), 'FStrategy' => $strategy, 'FMemo' => $memo));
  }
}