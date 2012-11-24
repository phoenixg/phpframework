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
 * The returned results when the database query is executed.
 *
 * @package lib.db
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-1
 */
class nbMysqlResult
{
    private $connect;
    private $rows = array ();
    private $fields = array ();
    private $isSuccess = null;
    private $resultType = MYSQLI_BOTH;

    /**
     * restruct the result, the object will be created when the MysqlAdeptor object's method query($SQL) executed
     *
     * @param  $result  the database query result
     * @param  $resultType  result type
     * @param  $needField   need field
     * @return MysqlResult object
     */
    public function __construct($result,$resultType = MYSQLI_BOTH, $needField=false)
    {
        $this->result = $result;
        $this->resultType = $resultType;
        //todo
        if ($result instanceof mysqli_result)
        {
            while ( $row = mysqli_fetch_array ( $this->result, $this->resultType ) )
            {
                $this->rows[] = $row ;
            }

            if ($needField)
            {
                while ( $field = mysqli_fetch_field ( $this->result ) )
                {
                    $this->fields[] = $field;
                }
            }
        }
        else
        {
            $this->isSuccess = $result;
        }
    }

    /**
     * free the Mysql result array
     */
    public function __destruct()
    {
        if ($this->result instanceof mysqli_result)
        {
            mysqli_free_result ($this->result );
            unset ( $this->rows );
        }
    }

    /**
     * Get all rows of the result.
     *
     * @return array[array[]]     It could be used to construct the MysqlTable object.
     */
    public function getAllRows()
    {
        return $this->rows;
    }

    /**
     * Get all fields of the returned result
     *
     * @return field object   field object's properties(name,table,maxlength,flags,type)
     * It could be used to construct the MysqlTable object.
     */
    public function getAllFields()
    {
        return $this->fields;
    }

    /**
     * Get the size of the result
     */
    public function getRowNum()
    {
        return count ( $this->rows );
    }

    /**
     * result is Empty or not
     */
    public function isEmpty()
    {
        if (count($this->rows) == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * query is success or not
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Set result type
     *
     * @param  int $type     result type
     */
    public function setResultType($type)
    {
        $this->resultType = $type;
    }

}