<?php
class Insert extends Criteria
{
  public $noSingleQuotesColumn = array();

  /**
   *
   * @param string $table
   * @return void
   */
  public function into($table)
  {
    $this->criteria['tables'] = $table;
  }

  /**
   *
   * @param string $values
   * @return string
   */
  public function value(array $values)
  {
    foreach ($values as $column => $value)
    {
      $value = mysql_escape_string($value);
      $this->criteria['values'][$column] = $value;
    }
  }

  /**
   *
   * @param string $date
   * @return void
   */
  public function setCreatedDate($date = '')
  {
    $this->criteria['values']['created_at'] = $date ? $date : date('Y-m-d', time());
  }

  /**
   *
   * @param string $time
   * @return void
   */
  public function setCreatedTime($time = '')
  {
    $this->criteria['values']['created_at'] = $time ? $time : date('Y-m-d h:i:s', time());
  }

  /**
   *
   * @param string $date
   * @return void
   */
  public function setUpdatedDate($date = '')
  {
    $this->criteria['values']['updated_at'] = $date ? $date : date('Y-m-d', time());
  }

  /**
   *
   * @param string $time
   * @return void
   */
  public function setUpdatedTime($time = '')
  {
    $this->criteria['values']['updated_at'] = $time ? $time : date('Y-m-d h:i:s', time());
  }
}
