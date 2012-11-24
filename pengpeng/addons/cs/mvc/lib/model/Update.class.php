<?php
class Update extends Criteria
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
   * @param array $values
   * @return void
   */
  public function set(array $values)
  {
    foreach ($values as $column => $value)
    {
      $value = mysql_escape_string($value);
      $this->criteria['values'][$column] = $value;
    }
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

  public function setUpdatedDate($date = '')
  {
    $this->criteria['values']['updated_at'] = $date ? $date : date('Y-m-d', time());
  }

  public function setUpdatedTime($time = '')
  {
    $this->criteria['values']['updated_at'] = $time ? $time : date('Y-m-d h:i:s', time());
  }
}
