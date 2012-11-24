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
 * 用于过滤字符串，用validate操作
 *
 * @package lib.util
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-11
 */
class TMFilterUtils
{
    /**
     * Filter password
     *
     * @param  string $param               the original password string
     * @return string                      the filtered password string
     */
    public static function filterPassword($param)
    {
        return preg_replace ( '/[\a\f\n\e\0\r\t\x0B]/is', "", trim ( $param ) );
    }

    /**
     * Filter text
     *
     * @param  string $str                                  the original text string
     * @param  boolean  $dirtyfilter                   whether do dirty filter
     *
     * @return string                                          the  filtered text string
     */
    public static function filterText($str, $dirtyfilter=true)
    {

        $str = trim ( $str );
        $str = preg_replace ( '/[\a\f\e\0\x0B]/is', "", $str );

        //$filter = constant("TMConfig::FILTER_ESC_ENABLE");

        //if ($filter)
        //{
        //    $str = preg_replace ( '/[\n\r\t]/is', "", $str );
        //}
        //$str = htmlspecialchars ( $str, ENT_QUOTES );
        //$str = self::filterTag ( $str );
        //$str = self::filterCommon ( $str );
        if($dirtyfilter)
        {
            $str = self::filterDirty ( $str );
        }
        return $str;
    }

    /**
     * Transfer the special string to the common string
     *
     * @param  string $str           the original string
     * @return string $str           the filtered string
     */
    public static function filterCommon($str)
    {
        $str = str_replace ( "&#032;", " ", $str );
        $str = preg_replace ( "/\\\$/", "&#036;", $str );
        $str = stripslashes ( $str );
        return $str;
    }

    /**
     * Filter the dirty glossary
     *
     * @param  string $str           the original string
     * @return string $str           the filtered string
     */
    public static function filterDirty($str)
    {
        $target = '';
        $str = str_replace ( '\\', "\\\\", self::stripslashes ( $str ) );
        $dirty = new qp_dirty ( PROJECT_ROOT . 'config/dirty.txt' );
        $dirty->replace_dirty ( $str, $target );
        return $target;
    }

    /**
     * Forbid the attack of XXS
     *
     * @param string $str  the formated string
     * @return string
     */
    public static function filterTag($str)
    {
        $str = str_ireplace ( "javascript", "j&#097;v&#097;script", $str );
        $str = str_ireplace ( "alert", "&#097;lert", $str );
        $str = str_ireplace ( "about:", "&#097;bout:", $str );
        $str = str_ireplace ( "onmouseover", "&#111;nmouseover", $str );
        $str = str_ireplace ( "onclick", "&#111;nclick", $str );
        $str = str_ireplace ( "onload", "&#111;nload", $str );
        $str = str_ireplace ( "onsubmit", "&#111;nsubmit", $str );
        $str = str_ireplace ( "<script", "&#60;script", $str );
        $str = str_ireplace ( "onerror", "&#111;nerror", $str );
        $str = str_ireplace ( "document.", "&#100;ocument.", $str );

        return $str;
    }

    /**
     * Filter the ip string
     *
     * @param  string $key          the ip string
     * @return boolean              whether the ip is correct
     */
    public static function filterIp($key)
    {
        $key = preg_replace("/[^0-9.]/", "", $key);
        return preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $key) ? $key : "0.0.0.0";
    }

    /**
     * Filters special characters in a string for use in a SQL statement
     *
     * @param  string $param      the sql parameter
     * @return string $param      the correct paramter
     */
    public static function filterSqlParameter($param)
    {
        $ts = new TMService();
        $db = $ts->getDb();
        $param  = $db->formatString($param);

        return $param;
    }

    /**
     * Check integer id
     *
     * @param int $id       the original id
     * @return boolean   the validate result
     */
    public static function checkId($id)
    {
        if (empty($id) || !is_numeric($id))
        {
            return false;
        }
        $id = intval ($id);
        if ($id < 0)
        {
            return false;
        }
        return true;
    }

    /**
     * Check the email is correct
     *
     * @param  string $param       the original id
     * @return boolean             the validate result
     */
    public static function checkEmail($param)
    {
        $str = trim ( $param );
        $str = preg_replace ( '/[\a\f\n\e\0\r\t\x0B\;\#\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/\s]/is', "", $str );
        $str = self::filterCommon ( $str );

        if (substr_count ( $str, '@' ) > 1)
        {
            return FALSE;
        }

        if (preg_match ( '/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/', $str ))
        {
            return $str;
        }
        else
        {
            return FALSE;
        }

    }

    /**
     * Check the zip code is correct
     *
     * @param  string $param       the original id
     * @param  integer $len        the correct zip code's length
     *
     * @return boolean             the validate result
     */
    public static function checkZipCode($param, $len = 6)
    {
        if (!is_numeric($param) || strlen($param) != $len)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Check the telephone is correct
     *
     * @param  string $tel         the original telephone number string
     * @return boolean             the validate result
     */
    public static function checkTel($tel)
    {
        return preg_match ("/^\d{11}$|^\d{3}-\d{7,8}$|^\d{4}-\d{7,8}$/", $tel);
    }

    /**
     * Check the qq number is correct
     *
     * @param  string $qq          the original qq number string
     * @return boolean             the validate result
     */
    public static function checkQQ($qq)
    {
        return preg_match ( "/^[1-9][0-9]{4,}$/", $qq );
    }

    /**
     * Check the string has not dirty glossary
     *
     * @param  string $str          the original string
     * @return boolean              the validate result
     */
    public static function dirtyValidate($str)
    {
        $str = str_replace ('\\', "\\\\", self::stripslashes ($str));
        $dirty = new qp_dirty(ROOT_PATH . 'dirty/dirty.txt');
        $ret = $dirty->has_dirty ($str);
        if (!empty ($ret))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Check the string length is correct
     *
     * @param  string $param          the original string
     * @param  integer $max           the correct string length
     * @return boolean              the validate result
     */
    public static function checkLen($param, $max = 300)
    {
        if (TMUtil::getStringLength($param) > $max)
        {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Un-quotes a quoted string
     *
     * @access public
     * @param  string $str
     * @return string  $str
     */
    private function stripslashes($str)
    {
        global $magic_quotes_gpc;

        if ($magic_quotes_gpc)
        {
            $str = stripslashes ( $str );
        }
        return $str;
    }
}