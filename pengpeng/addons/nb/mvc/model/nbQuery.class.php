<?php
class nbQuery
{
  private static $db;

  public static function getInstance()
  {
    if (! isset(self::$db))
    {
      $host = nbHelper::getConfig('cs-dao/host');
      $username = nbHelper::getConfig('cs-dao/user');
      $password = nbHelper::getConfig('cs-dao/password');
      $dbname = nbHelper::getConfig('cs-dao/dbname');

      self::$db = new mysqli($host, $username, $password, $dbname);
      self::$db->query('set names utf8');
    }
  }

  /**
   *
   * @param Select $c
   * @return mix
   */
  public static function selectOne($sql)
  {
    if ($temp = self::select($sql))
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

  public static function selectBool($sql)
  {
    return self::selectOne($sql) ? true : false;
  }

  public static function select($sql)
  {
    $result = self::query($sql);

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
  public static function selectWithTotal($sql)
  {
    $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
    $returnValue = array();
    $results = self::query($sql);

    while ($results && $row = $results->fetch_assoc())
    {
      $returnValue[] = $row;
    }

    $results = self::query('SELECT FOUND_ROWS() as `total`');
    while ($row = $results->fetch_assoc())
    {
      $total = $row;
    }

    return array($returnValue, $total['total']);
  }

  public static function insertOrUpdateOnDuplicate(Insert $c)
  {
  }

  public static function query($sql)
  {
    self::getInstance();

    if (nbLogHelper::logAble())
    {
      $nbQueryExplain = new nbQueryExplain();
      $info = $nbQueryExplain->explain(self::$db, $sql);
    }
    else
    {
      $info = '';
    }
    new nbLog($sql.$info);
    
    $result = self::$db->query($sql);
    if(strpos($sql, 'INTO'))
    {
     $result = self::$db->insert_id;
    }
    if ($result)
    {
      return $result;
    }
    else
    {
      new nbLog(self::$db->error);
      throw new nbAddonException(self::$db->error);
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
}