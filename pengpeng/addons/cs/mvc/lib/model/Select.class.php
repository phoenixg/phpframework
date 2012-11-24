<?php
class Select extends Criteria
{
  public $unionType = 'union_all';

  /**
   *
   * @param string $condition
   * @param array $token
   * @return void
   */
  public function where($condition, $token = array())
  {
    if ($token)
    {
      $token = (array)$token;

      foreach ($token as $t)
      {
        $condition = preg_replace('/\?/', mysql_escape_string($t), $condition, 1);
      }
    }

    $this->criteria['conditions'][] = $condition;
  }

  /**
   *
   * @param string $table
   * @param string $as
   * @return void
   */
  public function from($table, $as = '')
  {
    if (!$as)
    {
      $as = $table;
    }
    $this->criteria['tables'][$as] = $table;

    return $this;
  }

  /**
   *
   * @param string $column
   * @param string $as
   * @return void
   */
  public function column($column, $as = '')
  {
    if (!$as)
    {
      $as = $column;
    }
    $this->criteria['columns'][$as] = $column;
  }

  /**
   *
   * @param string $column
   * @param string $way
   * @return void
   */
  public function orderBy($column, $way = 'ASC')
  {
    $way = strtoupper($way);
    if ($way != 'ASC' && $way != 'DESC')
    {
      throw new CriteriaException('The way for order by only can be ASC or DESC');
    }
    $this->criteria['order'][$column] = $way;
  }

  /**
   *
   * @param string $column
   * @return void
   */
  public function groupBy($column)
  {
    $this->criteria['group'][] = $column;
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

  /**
   *
   * @param string $column
   * @return void
   */
  public function having($column)
  {
    $this->criteria['having'][] = $column;
  }

  /**
   *
   * @param Select $select
   * @return string
   */
  public function union(Select $select)
  {
    $this->criteria['union'][] = DB::convertToSelect($select);
  }

  /**
   * leftJoin method must be used after from method.
   *
   * @param string $table
   * @param string $as
   * @param string $condition
   * @return void
   */
  public function leftJoin($table, $as, $condition)
  {
    $this->criteria['tables'][$as] = array($table, $condition);
  }

  /**
   *
   * @param string $value
   * @return void
   */
  public function setTotal($value)
  {
    $this->criteria['total'] = $value;
  }
}
