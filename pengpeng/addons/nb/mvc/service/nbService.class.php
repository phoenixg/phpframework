<?php

/**
 * 数据库操作类
 *
 * @package lib.service
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMService.class.php 2008-9-6 by ianzhang
 */
class nbService {

    /**
     * @var TMMysqlAdapter
     *
     * @access private
     */
    protected $db;
    /**
     * @var array
     *
     * @access protected
     */
    protected static $dbArray = array();

    /**
     * 构造函数
     *
     * @param string $dbAlias     数据库配置的别名
     * @param boolean $autoCommit 是否自动提交
     */
    public function __construct($dbAlias = "default", $autoCommit = TRUE) {
        $this->db = $this->getConn($dbAlias, $autoCommit);
    }

    /**
     * get db adapter instance
     *
     * @access public
     * @return TMMysqlAdapter $db db adapter instance
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Get the connection database
     *
     * @param string $dbAlias  数据库配置别名
     * @param boolean $autoCommit is auto commit
     * @return $database    The connection database
     */
    protected function getConn($dbAlias = "default", $autoCommit)
    {
      $db = nbFactory::getInstance()->getFactory('cs-dao-db');

      if (!$db)
      {
        $host = nbConfigHelper::getConfig('cs-dao/host', __FILE__);
        $username = nbConfigHelper::getConfig('cs-dao/user', __FILE__);
        $password = nbConfigHelper::getConfig('cs-dao/password', __FILE__);
        $dbname = nbConfigHelper::getConfig('cs-dao/dbname', __FILE__);

        $db = new TMMysqlAdapter($host, $username, $password, $dbname);
        nbFactory::getInstance()->setFactory('cs-dao-db', $db);
      }

      return $db;
    }

    /**
     * The customer sql query
     *
     * @param  string $sql     the sql string
     * @return TMMysqlResult $object
     * @throws TMMysqlException
     */
    public function query($sql, $resultType = MYSQLI_ASSOC) {
        $result = $this->db->query($sql, $resultType);
        if (!$result->isEmpty()) {
            $rows = $result->getAllRows();
            return $rows;
        } else {
            return array();
        }
    }

    public function queryOne($sql) {
        $rs = $this->query($sql);
        if (count($rs) > 0) {
            $rs = array_shift($rs);
            if (1 == count($rs)) {
                $rs = current($rs);
                return $rs;
            }
            return $rs;
        } else {
            return null;
        }
    }

    /**
     * Set time in update or insert array
     *
     * @param array $field     the insert or update array
     * @param string $column    the presented time column name
     */
    public function setTimeForUpdateOrInsert(array &$field, $column = 'FTime') {
        $field [$column] = date('Y-m-d H:i:s');
    }

    /**
     * Set date in update or insert array
     *
     * @param array $field     the insert or update array
     * @param string $column    the presented date column name
     */
    public function setDateForUpdateOrInsert(array &$field, $column = 'FDate') {
        $field[$column] = date('Y-m-d');
    }

    /**
     * Get data count by where sql
     *
     * @param array $conditions  where condition, example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param string $table      the table name
     *
     * @throws TMMysqlException
     */
    public function getCount(array $conditions, $table, $forUpdate=false) {
        $sqlstr = $this->db->makeSQLString($conditions, 'count(*) c', $table);
        if ($forUpdate) {
            $sqlstr .= " for update";
        }
        $result = $this->db->query($sqlstr);
        $rows = $result->getAllRows();

        return (int) $rows[0]['c'];
    }

    /**
     * 只获取一行数据, 参数同select
     * @see select
     */
    public function selectOne($select, $table, array $conditions = array(), $limitArray= null, $otherArray = null, $resultType = MYSQLI_ASSOC) {
        $rs = $this->select($select, $table, $conditions, array(0, 1), $otherArray, $resultType);
        if (count($rs) > 0) {
            $rs = array_shift($rs);
            if (1 == count($rs)) {
                $rs = current($rs);
                return $rs;
            }
            return $rs;
        } else {
            return null;
        }
    }

    public function selectWithTotal($select, $table, array $conditions = array(), $limitArray= null, $otherArray = null, $resultType = MYSQLI_ASSOC) {
        $sqlstr = $this->db->makeSQLString($conditions, $select, $table, $limitArray, $otherArray);
        $sqlstr = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sqlstr);
        $results = $this->db->query($sqlstr, $resultType);

        $total = self::queryOne('SELECT FOUND_ROWS() as `total`');

        if (!$results->isEmpty()) {
            $rows = $results->getAllRows();
            return array($rows, $total);
        } else {
            return array(array(), 0);
        }
    }

    /**
     * Select data by where sql
     *
     * @param  array $conditions           where condition
     *                                     example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  string $select              the select columns of the sql
     * @param  string $table               the table name
     * @param  array $limitArray           the limit array, for example: array(0,10)
     * @param  array $otherArray           the other array, for example: array("orderby"=>'time deac')
     * @param  int $resultType             SQL返回数据的类型，包括MYSQLI_BOTH,MYSQLI_ASSOC,MYSQLI_NUM
     *
     * @return result                      the add's result rows or false
     * @throws TMMysqlException
     */
    public function select($select, $table, array $where = array(), $limitArray= null, $otherArray = null, $resultType = MYSQLI_ASSOC) {
        $qb = new nbQueryBuilder();
        $qb->addTable($table);
        $qb->addSelectFieldString($select);
        $qb->setCondition($this->praseWhere($where));
        if ($limitArray) {
            $qb->modifyLimit($limitArray[0], $limitArray[1]);
        }
        if ($otherArray) {
            if (isset($otherArray['orderBy'])) {
                list($column, $type) = explode(' ', $otherArray['orderBy']);
                $qb->addOrderBy($column, $type);
            }
        }

        return nbQuery::select($qb->toSelect());
    }

    public function selectColumn($select, $table, array $where = array(), $limitArray= null, $otherArray = null, $resultType = MYSQLI_ASSOC)
    {
      $rs = $this->select($select, $table, $where, $limitArray, $otherArray, $resultType);

      $return = array();
      foreach ($rs as $row)
      {
        if (count($row) === 1)
        {
          foreach ($row as $key => $value)
          {
            $return[] = $value;
          }
        }
        else
        {
          foreach ($row as $key => $value)
          {
            $return[$key][] = $value;
          }
        }
      }

      return $return;
    }

    /**
     * Select data by where sql for update
     *
     * @param  array $conditions           where condition
     *                                     example: array("FQQ" => '10000', "FUserId" => 1),array("eq" => array("FQQ" => '10000'))
     * @param  string $select              the select columns of the sql
     * @param  string $table               the table name
     * @param  array $limitArray           the limit array, for example: array(0,10)
     * @param  array $otherArray           the other array, for example: array("orderby")
     * @param  int $resultType             SQL返回数据的类型，包括MYSQLI_BOTH,MYSQLI_ASSOC,MYSQLI_NUM
     * @return result                      the add's result rows or false
     * @throws TMMysqlException
     */
    public function selectForUpdate($select, $table, array $conditions = array(), $limitArray= null, $otherArray = null, $resultType = MYSQLI_BOTH) {
        $sqlstr = $this->db->makeSQLString($conditions, $select, $table, $limitArray, $otherArray) . " for update";
        $result = $this->db->query($sqlstr, $resultType);
        if (!$result->isEmpty()) {
            $rows = $result->getAllRows();
            return $rows;
        } else {
            return array();
        }
    }

    /**
     * Add a new data
     *
     * @param  array $insertArray     the insert parameter array
     * @param  string $table          the table name
     * @param  boolean $delayed        if True, Add DELAYED in SQL. esp. True when the data DO NOT need to wait the returning, e.g. adding score
     * @return void
     * @throws TMMysqlException
     */
    public function insert($table, $insertValue, $delay=false) {
        $qb = new nbQueryBuilder();
        $qb->addTable($table);
        $qb->setInsertValue($insertValue);
        return nbQuery::query($qb->toInsert());
    }

    public function insertWithTime(array $insertArray, $table, $delay=false) {
        $this->setDateForUpdateOrInsert($insertArray);
        $this->setTimeForUpdateOrInsert($insertArray);
        $this->insert($insertArray, $table, $delay);

        return $this->db->getInsertId();
    }

    /**
     * 获取刚插入的数据id
     * @return int
     */
    public function getInsertId() {
        return $this->db->getInsertId();
    }

    /**
     * update or delete 操作影响的数据行数
     * @return int
     */
    public function getAffectedRowNum() {
        return $this->db->getAffectedRowNum();
    }

    /**
     * Update Data's information
     *
     * @param  array $fields           the update set array
     * @param  mixed $where            the update where string or array
     * @param  string $table           the table name
     *
     * @throws TMMysqlException
     */
    public function update($table, $updateValue, $where) {
        $qb = new nbQueryBuilder();
        $qb->addTable($table);
        $qb->setUpdateValue($updateValue);
        $qb->setCondition($this->praseWhere($where));
        return nbQuery::query($qb->toUpdate());
    }

    public function delete($table, $where) {
        $qb = new nbQueryBuilder();
        $qb->addTable($table);
        $qb->setCondition($this->praseWhere($where));

        return nbQuery::query($qb->toDelete());
    }

    private function praseWhere($where) {
        $conditions = array();
        foreach ($where as $key => $value) {
            $condition = array();

            if (strstr($key, '|')) {
                $temp = explode('|', $key);
                if (in_array('nq', $temp)) {
                    $key = str_replace('|nq', '', $key);
                    $condition['rightValueQuote'] = '';
                }

                if (in_array('or', $temp)) {
                    $key = str_replace('|or', '', $key);
                    $condition['connecter'] = 'OR';
                }

                if (in_array('hq', $temp)) {
                    $key = str_replace('|hq', '', $key);
                    $condition['rightValueQuote'] = "'";
                }
            }

            if (strstr($key, '|')) {
                list($key, $condition['operater']) = explode('|', $key);
            } else {
                $condition['operater'] = "=";
            }

            $condition['leftField'] = $key;
            $condition['rightValue'] = $value;

            $conditions[] = $condition;
        }

        return $conditions;
    }

    /**
     * Commit the DB modification, but you need to set $autoCommit as False.
     */
    public function commit() {
        $this->db->commit();
    }

    /**
     * Rollback the DB modification, but you need to set $autoCommit as False.
     */
    public function rollback() {
        $this->db->rollback();
    }

    /**
     * 开始事务
     *
     */
    public function startTransaction() {
        $this->db->startTransaction();
    }

    /**
     * Operate the state
     *
     * @param array $arrColOp     the column numeric operation,
     *                              example: array("FScore" => "+1"),
     *                                       array("FVoteCount" => "*5")
     * @param string $table         the table name
     * @param string $where          the where string
     * @param array $arrColSet    the column update set,
     *                              example: array("FQQ" => '10001', "FCity" => "shanghai")
     *
     * @return boolean true         the operate's result
     * @throws TMMysqlException
     */
    public function operateState($arrColOp, $table, $where=null, $arrColSet=array()) {
        return $this->db->operate($table, $arrColOp, $where, $arrColSet);
    }

    /**
     * Get the data count in one day
     *
     * @param  array $fields     the parameter fields array
     * @param  string $table     the table name
     * @param  string $column    the time column name
     * @param  string $length    the day string length
     *
     * @return int                              the one day count
     * @throws TMMysqlException
     */
    public function getCountOneDay(array $fields, $table, $column = "FTime", $length = "10") {
        $today = date("Y-m-d");
        $sqlstr = "select count(*) c from " . $table . " where LEFT(" . $column . "," . $length . ") ='" . $today . "'";
        foreach ($fields as $key => $field) {
            if (is_numeric($field) && intval($field) == $field && !in_array($key, TMConfig::mysqlStringColumns ())) {
                $sqlstr .= " and " . $key . " = " . $field;
            } else {
                $sqlstr .= " and " . $key . " = '" . $this->db->formatString($field) . "'";
            }
        }
        $result = $this->db->query($sqlstr);
        $rows = $result->getAllRows();
        if (!$rows) {
            return false;
        } else {
            return $rows [0] ['c'];
        }
    }

    public function dump($filePath, $ignoreerrors = false) {
        $file_content = file($filePath);
        $query = "";
        foreach ($file_content as $sql_line) {
            $tsl = trim($sql_line);
            if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
                $query .= $sql_line;
                if (preg_match("/;\s*$/", $sql_line)) {
                    $query = str_replace(";", "", "$query");
                    $result = $this->query($query);
                    $query = "";
                }
            }
        }
    }

}