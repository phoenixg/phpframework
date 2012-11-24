<?php
class Delete extends Criteria
{
  public $noSingleQuotesColumn = array();

  /**
   *
   * @param string $table
   * @return void
   */
  public function from($table)
  {
    $this->criteria['tables'] = $table;
  }

  /**
   *
   * @param string $condition
   * @param array $token
   * @return void
   */
  public function where($condition, $token = array())
  {
    $token = (array)$token;

    foreach ($token as $t)
    {
      $condition = preg_replace('/\?/', mysql_escape_string($t), $condition, 1);
    }

    $this->criteria['conditions'][] = $condition;
  }

  /**
   *
   * @param integer $paraA
   * @param integer $paraB
   * @return void
   */
  public function limit($paraA, $paraB = 0)
  {
    if ($paraB)
    {
      $this->criteria['limit'] = array($paraA, $paraB);
    }
    else
    {
      $this->criteria['limit'] = $num;
    }
  }
}
