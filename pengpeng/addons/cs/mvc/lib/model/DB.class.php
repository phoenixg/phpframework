<?php
class DB
{
  private static $db;

  public static function getInstance()
  {
    if (! isset(self::$db))
    {
      $config = nbHelper::getConfig('cs-mvc/db');

      self::$db = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);
      self::$db->query('set names utf8');
    }
  }

  /**
   *
   * @param Select $c
   * @return mix
   */
  public static function selectOne(Select $c)
  {
    if ($temp = self::select($c))
    {
      $temp = current($temp);

      if (1 == count($temp))
      {
        $temp = current($temp);
      }

      return $temp;
    }
    else
    {
      return false;
    }
  }

  public static function selectBool(Select $c)
  {
    return DB::selectOne($c) ? true : false;
  }

  public static function select(Select $c)
  {
    $result = self::query(self::convertToSelect($c));

    $r = array();

    while ($row = $result->fetch_assoc())
    {
      $r[] = $row;
    }

    return $r;
  }

  /**
   * @param Select $c
   * @return unknown_type
   */
  public static function selectWithTotal(Select $c)
  {
    $c->setTotal(true);
    $sql = DB::convertToSelect($c);

    $r = array();
    $results = self::query($sql);

    while ($results && $row = $results->fetch_assoc())
    {
      $r[] = $row;
    }

    $results = self::query('SELECT FOUND_ROWS() as `total`');
    while ($row = $results->fetch_assoc())
    {
      $total = $row;
    }

    return array($r, $total['total']);
  }

  public static function update(Update $c)
  {
    $sql = self::convertToUpdate($c);

    $result = self::query($sql);
  }

  public static function insert(Insert $c)
  {
    $sql = self::convertToInsert($c);

    $result = self::query($sql);

    return self::$db->insert_id;
  }

  public static function insertOrUpdateOnDuplicate(Insert $c)
  {
  }

  public static function delete(Delete $c)
  {
    $sql = self::convertToDelete($c);

    $result = self::query($sql);
  }

  public static function query($sql)
  {
    DB::getInstance();

    new nbLog($sql);

    $result = self::$db->query($sql);

    if ($result)
    {
      return $result;
    }
    else
    {
//      print_r(self::$db->error);
//      print_r($sql);
    }

  }

  public static function startTransaction()
  {

  }

  public static function commitTransaction()
  {

  }

  public static function rollbackTransaction()
  {

  }

  public static function convertToSelect(Select $c)
  {
    $sql = $c->getCriteria();

    if (isset($sql['union']))
    {
      $temp = array();
      foreach ($sql['union'] as $unionName => $sebSelect)
      {
        $temp[] = ' (' . $sebSelect . ') ';
      }
      $sql['tables'][isset($sql['union_alias']) ? $sql['union_alias'] : 'noname'] = '(' . implode(' ' . (isset($sql['union_type']) && $sql['union_type'] == 'union_all') ? 'UNION' : 'UNION ALL' . ' ', $temp) . ')';
    }

    $sqlString = 'SELECT';
    if (isset($sql['columns']) && !empty($sql['columns']))
    {
      $select = array();
      foreach ($sql['columns'] as $key => $value)
      {
        $select[] = is_numeric($key) ? ' ' . $value : ' ' . $value . ' AS `' . $key . '`';
      }
      $sqlString .= implode(',', $select);
    }
    else
    {
      $sqlString .= ' *';
    }

    $sqlString .= ' FROM';
    if (isset($sql['tables']) && !empty($sql['tables']))
    {
      $from = array();
      foreach ($sql['tables'] as $key => $value)
      {
        if (is_array($value))
        {
          $from[count($from) - 1] .= ' LEFT JOIN ' . $value[0] . ' AS `' . $key . '` ON (' . $value[1] . ')';
        }
        else
        {
          $from[] = is_numeric($key) ? ' ' . $value : ' ' . $value . ' AS `' . $key . '`';
        }
      }
      $sqlString .= implode(',', $from);
    }

    $sqlString .= ' WHERE';
    if (isset($sql['conditions']) && !empty($sql['conditions']))
    {
      $where = array();
      foreach ($sql['conditions'] as $key => $value)
      {
        $where[] = is_numeric($key) ? ' (' . $value . ')' : ' ' . $value . ' ';
      }
      $sqlString .= implode(' AND', $where);
    }
    else
    {
      $sqlString .= ' TRUE';
    }

    if (isset($sql['total']) && $sql['total'])
    {
      $sqlString = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sqlString);
    }

    if (isset($sql['group']))
    {
      $sqlString .= ' GROUP BY ' . $sql['group'][0];
    }

    if (isset($sql['order']) && is_array($sql['order']))
    {
      $sqlString .= ' ORDER BY ';
      $order = array();

      foreach ($sql['order'] as $column => $way)
      {
        if (is_numeric($column))
        {
          $column = $way;
          $way = 'ASC';
        }

        $order[] = '`' . $column . '` ' . $way;
      }

      $sqlString .= implode(', ', $order);
    }

    if (isset($sql['limit']))
    {
      if (is_array($sql['limit']))
      {
        $sqlString .= ' LIMIT ' . $sql['limit'][0] . ', ' . $sql['limit'][1];
      }
      else
      {
        $sqlString .= ' LIMIT ' . $sql['limit'];
      }
    }

    return $sqlString;
  }

  public static function convertToUpdate(Criteria $c)
  {
    $sql = $c->getCriteria();

    $sqlString = 'UPDATE '.$sql['tables'].' SET ';

    foreach ($sql['values'] as $key => $value)
    {
      if (is_numeric($value))
      {
        $subArray[] = "`$key` = $value";
      }
      else if (in_array($key, $c->noSingleQuotesColumn))
      {
        $subArray[] = "`$key` = $value";
      }
      else
      {
        $subArray[] = "`$key` = '$value'";
      }
    }

    $sqlString .= implode(",", $subArray);

    $sqlString .= ' WHERE';
    if (isset($sql['conditions']) && !empty($sql['conditions']))
    {
      $where = array();
      foreach ($sql['conditions'] as $key => $value)
      {
        $where[] = is_numeric($key) ? ' (' . $value . ')' : ' ' . $value . ' ';
      }
      $sqlString .= implode(' AND', $where);
    }
    else
    {
      $sqlString .= ' TRUE';
    }

    if (isset($sql['limit']))
    {
      if (is_array($sql['limit']))
      {
        $sqlString .= ' LIMIT ' . $sql['limit'][0] . ', ' . $sql['limit'][1];
      }
      else
      {
        $sqlString .= ' LIMIT ' . $sql['limit'];
      }
    }

    return $sqlString;
  }

  public static function convertToInsert(Insert $c)
  {
    $sql = $c->getCriteria();

    $sqlString = 'INSERT INTO '.$sql['tables'];

    $keys = array_keys($sql['values']);

    $sqlString .= " (`". implode('`, `', $keys)."`) VALUES (";

    foreach (array_values($sql['values']) as $key => $value)
    {
      if (is_numeric($value))
      {
        $subArray[] = $value;
      }
      else if (in_array($keys[$key], $c->noSingleQuotesColumn))
      {
        $subArray[] = $value;
      }
      else
      {
        $subArray[] = "'".$value."'";
      }
    }

    $sqlString .= implode(",", $subArray);
    $sqlString .= ")";

    return $sqlString;
  }

  public static function convertToDelete(Criteria $c)
  {
    $sql = $c->getCriteria();

    $sqlString = 'DELETE FROM '.$sql['tables'];

    $sqlString .= ' WHERE';
    if (isset($sql['conditions']) && !empty($sql['conditions']))
    {
      $where = array();
      foreach ($sql['conditions'] as $key => $value)
      {
        $where[] = is_numeric($key) ? ' (' . $value . ')' : ' ' . $value . ' ';
      }
      $sqlString .= implode(' AND', $where);
    }
    else
    {
      $sqlString .= ' TRUE';
    }

    if (isset($sql['limit']))
    {
      if (is_array($sql['limit']))
      {
        $sqlString .= ' LIMIT ' . $sql['limit'][0] . ', ' . $sql['limit'][1];
      }
      else
      {
        $sqlString .= ' LIMIT ' . $sql['limit'];
      }
    }

    return $sqlString;
  }

  public function selectForJoinGroup(Criteria $c)
  {
    $r = DB::select($c);

    $sql = $c->getCriteria();

    foreach ($r as $row)
    {
      foreach ($row as $columnName => $value)
      {
        $tempArray = explode('.', $sql['columns'][$columnName]);

        if (isset($tempArray[1]) && $tempArray[0] != key($sql['tables']))
        {
          $temp[$columnName][] = $value;
        }
        else
        {
          $temp[$columnName] = $value;
        }
      }
    }

    return $temp;
  }
}
