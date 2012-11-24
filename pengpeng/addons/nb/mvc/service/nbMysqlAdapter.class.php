<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * The Mysql database connection class
 *
 * @package lib.db
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class nbMysqlAdapter
{
    private $connection;
    private $affectedRowNum;
    private $insertId;
    private static $arr_operator = array ('+', '-', '*', '/', '%' );
    private static $_comparisons = array(
                  'eq'     => '=',
                  'in'     => 'IN',
                  'neq'    => '!=',
                  'gt'     => '>',
                  'egt'    => '>=',
                  'lt'     => '<',
                  'elt'    => '<=',
                  'like'   => 'LIKE',
                  'notnull'=> 'IS NOT NULL',
                  '='     => '=',
                  '!='    => '!=',
                  '>'     => '>',
                  '>='    => '>=',
                  '<'     => '<',
                  '<='    => '<=',
                  'IS NOT NULL'=> 'IS NOT NULL'
    );

    /**
     * Initialize the Mysql db connection, set the commit as manual, and set the default encode as UTF-8
     *
     * @param  $dbhost      host name
     * @param  $dbuser        login user
     * @param  $dbpasswd       login password
     * @param  $database        used database
     *
     * @throws nbCoreException
     */
    public function __construct($dbhost, $dbuser, $dbpasswd, $database,$autoCommit=TRUE,$code='UTF8')
    {
        $this->connection = mysqli_connect ( $dbhost, $dbuser, $dbpasswd, $database );

        if (! $this->connection)
        {
            throw new nbCoreException("Can't connect the database ".$database." in ".$dbuser."@".$dbhost."used password ".$dbpasswd." ".mysqli_connect_error($this->connection));
        }
        mysqli_query ( $this->connection, "SET NAMES '".$code."'" );
        mysqli_autocommit ( $this->connection, $autoCommit );
    }

    /**
     * Close the Mysql db connection
     *
     */
    public function __destruct()
    {
        mysqli_close ( $this->connection );
        $this->connection = null;
    }

    /**
     * Execute the database query
     *
     * @param  string $sql     sql command
     * @param  int $resultType return type
     * @return nbMysqlResult object
     */
    public function query($sql, $resultType = MYSQLI_ASSOC)
    {
        new nbLog($sql);

        $result = mysqli_query ($this->connection, $sql);

        if ($result === false)
        {
          new nbLog($sql.": Query String error." . mysqli_error($this->connection), 'SQL');
          throw new nbCoreException($sql.": Query String error." . mysqli_error($this->connection));
        }

        $this->affectedRowNum = mysqli_affected_rows($this->connection);
        $this->insertId = mysqli_insert_id($this->connection);
        return new nbMysqlResult ($result,$resultType);
    }

    /**
     * Create the query string
     *
     * @param  array $fields        the AND YES where condition present by array,
     *                              example: array("FQQ" => '10000', "FUserId" => 1),
     *                              present: "FQQ = '10000' and FUserId = 1" in the where clause
     * @param  array $notFields     the AND NOT YES where condition present by array,
     *                              example: array("FQQ" => '10000', "FUserId" => 1),
     *                              present: "FQQ != '10000' and FUserId != 1" in the where clause
     * @param  string $select       the select string
     * @param  string $table        the table name string
      * @param  int $begin           the result begin number
      * @param  int $count           the result count number
      * @param  string $orderby      the sql order
     *
     * @return string $query        the query string
     */
    public function makeQueryString(array $fields, array $notFields, $table, $select = "*", $begin = 0, $count = 0, $orderby = "FTime DESC")
    {
        $arrayStringColumn = array('FQQ','FDesId','FSrcId','FSrcQQ','FDesQQ');
        $query = "select " . $select . " from " . $table;
        if (count ( $fields ) != 0 || count ( $notFields ) != 0)
        {
            $query .= " where ";
            $i = 1;
            foreach ( $fields as $key => $field )
            {
                if (!empty($arrayStringColumn))
                {
                    if (in_array($key,$arrayStringColumn))
                    {
                        $field = self::fieldToString($field);
                    }
                    else if (is_numeric($field))
                    {
                        $field = intval($field);
                    }
                }
                if ($i == 1)
                {
                    if (is_string ( $field ))
                    {
                        $query .= $key . " = '" . $field . "'";
                    } else {
                        $query .= $key . " = " .  $field ;
                    }
                }
                else
                {
                    if (is_string ( $field ))
                    {
                        $query .= " and " . $key . " = '" . $this->formatString ( $field ) . "'";
                    }
                    else
                    {
                        $query .= " and " . $key . " = " . intval ( $field );
                    }
                }
                $i ++;
            }

            foreach ( $notFields as $key => $field )
            {
                if (!empty($arrayStringColumn))
                {
                    if (in_array($key,$arrayStringColumn))
                    {
                        $field = self::fieldToString($field);
                    }
                    else if (is_numeric($field))
                    {
                        $field = intval($field);
                    }
                }
                if ($i == 1)
                {
                    if (is_string ( $field ))
                    {
                        $query .= $key . " != '" . $this->formatString ( $field ) . "'";
                    }
                    else
                    {
                        $query .= $key . " != " . $field;
                    }
                }
                else
                {
                    if (is_string ( $field ))
                    {
                        $query .= " and " . $key . " != '" . $this->formatString ( $field ) . "'";
                    }
                    else
                    {
                        $query .= " and " . $key . " != " . intval ( $field );
                    }
                }
                $i++;
            }
        }

        if (!empty($orderby))
        {
            $query .= ' order by ' . $orderby;
        }

        if ($count != 0)
        {
            $query .= ' limit ' . $begin . ',' . $count;
        }

        return $query;
    }

    /**
     * return the result querystring of parse array
     *
     * @param  mixed $conditions    array which stores the "where" clause, as array('eq' => array('FQQ'=>'123456'))
     * @param  string $selectFields  'FQQ,FScore'
     * @param  string $table           the table include join
     * @param  array $limitArray    the limit offset, count. For example, array(0,2). start = 0, offset =2;
      * @param  array $otherArray    the other conditions includes group by, order by
      *                                                  $otherArray['orderby'],$otherArray['groupby'],$otherArray['having']
     * @return string $query        the query string
     *
     */
//    public function makeSQLString($conditions,$selectFields,$table,$limitArray = null,$otherArray = null)
//    {
//      $qb = new nbQueryBuilder();
//      $qb->addTable($table);
//      $qb->addSelectFieldString($selectFields);
//      return nbQuery::select($qb->toSelect());
//
////      p($qb->toSelect());
////
////        $query  = "select " . $selectFields . " from " . $table;
////        $query  .= " where " . $this->parseWhere($conditions) . $this->parseOthers($otherArray) . $this->parseCount($limitArray);
////        return $query;
//    }

    /**
     * parseCount
     * @desc    parse  limit
     * @param   array  $countArray
     * @return  string $query
     */
    protected function parseCount($countArray)
    {
        $query = '';
        if (is_array($countArray))
        {
            $query .= ' limit ' . $countArray[0] . ' , ' . $countArray[1];
        }
        else
        {
            $query .= '';
        }
        return $query;
    }

    /**
     * parseOthers
     * @desc     parse others include group by, order by, having and more ...
     * @param    array $otherArray
     * @return   string $query
     */
    protected function parseOthers($otherArray)
    {
        $query = '';
        if (is_array($otherArray))
        {
            if (!empty($otherArray['groupby']))
            {
                $query .= ' group by ' . $otherArray['groupby'];
            }
            if (!empty($otherArray['orderby']))
            {
                $query .= ' order by ' . $otherArray['orderby'];
            }
            if (!empty($otherArray['having']))
            {
                $query .= ' having ' . $otherArray['having'];
            }
        }
        else
        {
            $query .= '';
        }
        return $query;
    }

    /**
     * 将传入参数转变为字符串
     * 支持一维数组
     * @param unknown_type|array $value
     * @return string|array
     */
    protected static function fieldToString($value) {
        if (is_array($value)) {
            $newValue = array();
            foreach ($value as $index => $v) {
                $newValue[] = strval($v);
            }
            return $newValue;
        }
        else {
            return strval($value);
        }
    }

    /**
     * parseWhere
     * @desc     parse where conditions
     * @param    array $fields - format as array("eq"=>array("fieldname"=>value))
     *                     "eq" means equal, you could find the string definition at the beginning of this file
     * @return   string $query
     */
    protected function parseWhere($fields)
    {

        $arrayStringColumn = array('FQQ','FDesId','FSrcId','FInviterQQ','FInvitedQQ', 'FInviterId');
        $query = "1 and ";

        $haveQuotes = true;
        $nextOperater = 'and';
        if (is_array($fields))
        {
            foreach ( $fields as $key => $fieldsons )
            {
                if (1)//$fieldsons != null
                {
                  if (strstr($key, '|'))
                  {
                    $temp = explode('|', $key);
                    if (in_array('n', $temp))
                    {
                      $key = str_replace('|n', '', $key);
                      $haveQuotes = false;
                    }

                    if (in_array('o', $temp))
                    {
                      $key = str_replace('|o', '', $key);
                      $nextOperater = 'or';
                    }

                    if (in_array('h', $temp))
                    {
                      $key = str_replace('|h', '', $key);
                      $haveQuotes = true;
                    }
                  }

                  if (strstr($key, '|'))
                  {
                    list($key, $comparisonKey) = explode('|', $key);
                  }
                  else
                  {
                    $comparisonKey = "eq";
                  }

                    if (!is_array($fieldsons))
                    {
                        $keyson = $key;
                        $field = $fieldsons;

                        /*if (is_numeric($field))
                        {
                            $field = intval($field);
                        } */
                        if (!empty($arrayStringColumn))
                        {
                            if (in_array($keyson, $arrayStringColumn))
                            {
                                $field = self::fieldToString($field);
                            }
                        }

                        if ($haveQuotes === false)
                        {
                          $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." " .  $field." $nextOperater ";
                        }
                        else if (is_array($field)) {
                            $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." " . $this->formatArrayIn ( $field ) . " $nextOperater ";
                        }
                        elseif (is_string($field))
                        {
                            $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." '" . $this->formatstring ( $field ) . "' $nextOperater ";
                        }
                        else if ($field === NULL)
                        {
                            $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." null $nextOperater ";
                        }
                        else
                        {
                            $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." " .  $field." $nextOperater ";
                        }
                    }
                    else
                    {
                        foreach ((array)$fieldsons as $keyson => $field)
                        {

                            /*if (is_numeric($field))
                            {
                                $field = intval($field);
                            }*/
                            if (!empty($arrayStringColumn))
                            {
                                if (in_array($keyson,$arrayStringColumn))
                                {
                                    $field = self::fieldToString($field);
                                }
                            }

                            $comparisonKey = $key;
                            if (is_array($field)) {
                                $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." " . $this->formatArrayIn ( $field ) . " $nextOperater ";
                            }
                            else if (is_string($field))
                            {
                                $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." '" . $this->formatstring ( $field ) . "' $nextOperater ";
                            }
                            else if ($field === NULL)
                            {
                                if (self::$_comparisons[$comparisonKey] == '=') {
                                    $query .= $keyson . " is null $nextOperater ";
                                }
                                elseif (self::$_comparisons[$comparisonKey] == '!=') {
                                    $query .= $keyson . " is not null $nextOperater ";
                                }
                                else {
                                    throw new nbCoreException("Make Query Failed: the value of $keyson is null, and $keyson $comparisonKey null is not supported, ");
                                }
                            }
                            else
                            {
                                $query .= $keyson . " ".self::$_comparisons[$comparisonKey]." " .  $field." $nextOperater ";
                            }
                        }
                    }
                }
            }

            $query = preg_replace('/'.$nextOperater.' $/', '', $query);
        }
        else
        {
            $query = '';
        }

        return $query;
    }

    /**
     * Add data row for application
     *
     * @param  array $array            the insert parameter array,
     *                                 example: array("FQQ" => '10000', "FUserId" => 2)
     * @param  string $table           the table name
     * @param  boolean $delayed        if True, Add DELAYED in SQL. esp. True when the data DO NOT need to wait the returning, e.g. adding score
     *
     * @throws nbCoreException
    */
    public function doInsert(array $array, $table, $delayed=false)
    {
        $formated_array = $this->compile_insert_string ( $array );
        $delaySql = "";
        if ($delayed === true) {
            $delaySql = " DELAYED ";
        }
        $queryString = 'INSERT '.$delaySql.' INTO ' . $table . ' (' . $formated_array ['FIELD_NAMES'] . ') VALUES(' . $formated_array ['FIELD_VALUES'] . ')';

        $result = $this->query($queryString );
        if (!$result->isSuccess())
        {
            throw new nbCoreException($queryString.": Insert Failed, " . mysqli_error($this->connection));
        }
        return $result->isSuccess();
    }

    /**
     * Update data
     *
     * @param  string $table the table name
     * @param  array $array the update set array
     * @param  string $where the update where string
     *
     * @throws nbCoreException
     */
    public function doUpdate($table, $array, $where = '')
    {
        $string = $this->compile_update_string ( $array );
        $sql = 'UPDATE ' . $table . ' SET ' . $string;

        if (is_array($where) && !empty($where))
        {
            $sql .= ' WHERE ' . $this->parseWhere($where);
        }
        else if ($where)
        {
            $sql .= ' WHERE ' . $where;
        }
        $result = $this->query($sql );
        if (!$result->isSuccess())
        {
            throw new nbCoreException($sql.": Update Failed");
        }
    }

    /**
     * Update data
     *
     * @param  string $table           the table name
     * @param  array  $array          the update set array
     * @param  array  $conditionArray  the condition set Array
     *
     * @throws nbCoreException
     */
    public function doUpdateX($table, $array, $conditionArray)
    {
        $string = $this->compile_update_string ( $array );
        $sql = 'UPDATE ' . $table . ' SET ' . $string;

        if (is_array($conditionArray) && !empty($conditionArray))
        {
            $sql .= ' WHERE 1' . $this->parseWhere($conditionArray);
        }

        $result = $this->query($sql);
        if (!$result->isSuccess())
        {
            throw new nbCoreException($sql.": Update Failed!");
        }
    }

    /**
     * Numeric operation in the numeric column and update set operation
     *
     * @param string $table         the table name
     * @param array $arrColOp     the column numeric operation,
     *                              example: array("FScore" => "+1"),
     *                                       array("FVoteCount" => "*5")
     * @param string $where          the where string
     * @param array $arrColSet    the column update set,
     *                              example: array("FQQ" => '10001', "FCity" => "shanghai")
     *
     * @return boolean true
     * @throws nbCoreException
     */
    public function operate($table, $arrColOp, $where = null, $arrColSet=array())
    {
        if (! is_array ( $arrColOp ) || empty ( $arrColOp ))
        {
            throw new nbCoreException('operation param must be not empty array');
        }

        $arr_str_op = array ();
        foreach ( $arrColOp as $col => $str )
        {

            $str = preg_replace ( '/\s+/', '', $str );

            $operator = $str [0];
            if (!in_array($operator, self::$arr_operator))
            {
                throw new nbCoreException($operator . ' operator for ' . $col . ' error');
            }

            $value = substr ( $str, 1 );
            if (! is_numeric ( $value ))
            {
                throw new nbCoreException($value . ' operation value for ' . $col . ' must be number');
            }
            $arr_str_op [] = $col . '=' . $col . $operator . $value;
        }

        $list_str_op = join ( ',', $arr_str_op );

        if (! empty ( $arrColSet ) && is_array ( $arrColSet ))
        {
            $str_col_set = $this->compile_update_string ( $arrColSet );
            $list_str_op .= ',' . $str_col_set;
        }

        //produce where sql in update set operation
        $sql = 'UPDATE ' . $table . ' SET ' . $list_str_op;
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        //query
        $result = $this->query($sql);
        if (! $result->isSuccess())
        {
            throw new nbCoreException($sql.": Update State Failed");
        }
        return true;
    }

    /**
     * 开始事务
     *
     */
    public function startTransaction()
    {
        mysqli_query ($this->connection, "start transaction");
    }


    /**
     * mysqli_commit
     *
     * @throws nbCoreException
     */
    public function commit()
    {
        if (! mysqli_commit ( $this->connection ))
        {
            throw new nbCoreException("commit error:" . mysqli_error($this->connection));
        }
    }

    /**
     * mysqli_rollback
     *
     * @throws nbCoreException
     */
    public function rollback()
    {
        if (! mysqli_rollback ( $this->connection ))
        {
            throw new nbCoreException("rollback error:" . mysqli_error($this->connection));
        }
    }

    /**
     * You can get the reference from mysqli_affectedRow in PHP manual.
     *
     * @return affected row number  integer
     */
    public function getAffectedRowNum()
    {
        return $this->affectedRowNum;
    }

    /**
     * get the last AUTO_INCREMENT ID
     *
     * @return insertId
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * mysqli_real_escape_string
     * @param string $sql sql string
     */
    private function filterSQL($sql)
    {
        return mysqli_real_escape_string ( $sql );
    }

    /**
     * format  insert set
     *
     * @param array $data
     * @return formated $data
     */
    private function compile_insert_string($data)
    {
        $field_names = '';
        $field_values = '';

        foreach ( $data as $k => $v )
        {
            $field_names .= "`{$k}`,";

            if (is_string($v))
            {
                $field_values .= "'" . $this->formatString ( $v ) . "',";
            }
            else
            {
                $field_values .= intval ( $v ) . ",";
            }
        }

        $field_names = preg_replace ( "/,$/", "", $field_names );
        $field_values = preg_replace ( "/,$/", "", $field_values );

        return array ('FIELD_NAMES' => $field_names, 'FIELD_VALUES' => $field_values );
    }

    /**
     * format update set
     *
     * @param array $data
     * @return formated $data
     */
    private function compile_update_string($data)
    {
        $return = '';
        foreach ( $data as $k => $v )
        {
            if (is_string($v))
            {
                $return .= "`$k`='" . $this->formatString ( $v ) . "',";
            }
            else
            {
                $return .= "`$k`=" . intval ( $v ) . ",";
            }
        }
        $return = preg_replace ( "/,$/", "", $return );

        return $return;
    }

    public function formatArrayIn($arr) {
        $str = '(';
        foreach($arr as $index => $v) {
            if (is_string($v)) {
                $str .= "'".$this->formatString($v)."',";
            }
            else {
                $str .= "".$this->formatString($v).",";
            }
        }
        $str = rtrim($str, ',');
        $str .= ')';
        return $str;
    }

    /**
     * Escapes special characters in a string for use in a SQL statement
     *
     * @param string $str    the query sql string
     * @return string $str   the formatted string
     */
    public function formatString($str)
    {
        if (get_magic_quotes_gpc ())
        {
            //$str = stripslashes ( $str );

        }
        if (! is_numeric ( $str ))
        {

            $str = mysqli_real_escape_string ( $this->connection, $str );
        }
        return $str;
    }
}
