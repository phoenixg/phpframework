<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | Tencent PHP Library.                                                 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2005 Tencent Inc. All Rights Reserved.            |
// +----------------------------------------------------------------------+
// | Authors: The Internet Services Dept., Tencent.                       |
// |          hyperjiang <hyperjiang@tencent.com>                         |
// +----------------------------------------------------------------------+

/**
 * @package lib
 * @file    qp_dirty.php ( orginal: qp_dirty.php )
 * @version 1.0
 * @author  hyperjiang
 * @date    2005/11/14
 * @brief   class for filtrate dirty words.
 */


/**
 * QP DIRTY lib class.
 */
class qp_dirty
{
    /**
     * @access  private
     * @var     resource    File name.
     */
    var $file_name = "";

    /**
     * @access  private
     * @var     array       Dirty words.
     */
    var $dirty_words;

    /**
     * @access  private
     * @var     string      Separator.
     */
    var $sep = '|';

    /**
     * @access  private
     * @var     string      Note.
     */
    var $note = '#';

    /**
     * @access  private
     * @var     int         Level.
     * @note    0: all levers, other: the higher the value, the lower the lever.
     */
    var $lev = 0;

    /**
     * @access  private
     * @var     string      Mark.
     */
    var $mark = '$';


    /* {{{ function qp_dirty( $filename, $lev, $sep, $note, $mark ) */
    /**
     * Initialize.
     *
     * @param   string  $filename   File name.
     * @param   int     $lev        Level.
     * @param   string  $sep        Separator.
     * @param   string  $note       Note.
     * @param   string  $mark       Mark.
     * @return          0: suc, other: fail.
     */
    function __construct( $filename = '', $lev = 0, $sep = '|', $note = '#', $mark = '$' )
    {
        $this->file_name    = $filename;
        $this->sep          = $sep;
        $this->note         = $note;
        $this->lev          = $lev;
        $this->mark         = $mark;
        if ( !empty($filename) ) {
            $this->read_dirty( $filename );
        } else {
            return(-1);
        } // if
        return(0);
    }
    /* }}} */

    function __destruct()
    {
        
    }


    /* {{{ function set_file( $filename ) */
    /**
     * Set file.
     *
     * @param   string  $filename   File name.
     */
    function set_file( $filename )
    {
        $this->file_name = $filename;
        return(0);
    }
    /* }}} */


    /* {{{ function set_level( $lev ) */
    /**
     * Set level.
     *
     * @param   int     $lev        Level.
     */
    function set_level( $lev = 0 )
    {
        $this->lev = $lev;
        return(0);
    }
    /* }}} */


    /* {{{ function set_sep( $sep ) */
    /**
     * Set separator.
     *
     * @param   string  $sep        Separator.
     */
    function set_sep( $sep = '|' )
    {
        $this->sep = $sep;
        return(0);
    }
    /* }}} */


    /* {{{ function set_note( $note ) */
    /**
     * Set note.
     *
     * @param   string  $note       Note.
     */
    function set_note( $note = '#' )
    {
        $this->note = $note;
        return(0);
    }
    /* }}} */


    /* {{{ function set_mark( $mark ) */
    /**
     * Set mark.
     *
     * @param   string  $mark       Mark.
     */
    function set_mark( $mark = '$' )
    {
        $this->mark = $mark;
        return(0);
    }
    /* }}} */


    /* {{{ function read_dirty( $filename ) */
    /**
     * Read dirty words from file.
     *
     * @return  int     0: ok, other: fail.
     */
    function read_dirty( $filename = '' )
    {
        if ( empty($filename) ) {
            $filename = $this->file_name;
        } // if

        $lines = file( $filename );
        if ( empty($lines) ) {
            return(-1);
        } // if

        $level = 1;
        foreach ( $lines as $line ) {
            $line = trim( $line );
            if ( empty($line) ) {
                continue;
            } // if
            if ( $this->note == $line[0] ) {
                continue;
            } // if
            if ( $this->mark == $line[0] ) {
                $tmp    = explode( $this->mark, $line );
                $level  = trim( @$tmp[1] );
                continue;
            } // if
            $word = explode( $this->sep, $line );
            $this->dirty_words[ trim($word[0]) ] = $level;
        } // foreach
        return(0);
    }
    /* }}} */


    /* {{{ function is_dirty( $word ) */
    /**
     * Check if a word is dirty.
     *
     * @param   string  $word       Note.
     * @return  int     1: dirty, 0: not dirty.
     */
    function is_dirty( $word )
    {
        if ( !empty($this->dirty_words[$word])
                && ( ($this->dirty_words[$word] <= $this->lev)
                        || 0 == $this->lev ) ) {
            return(1);
        } // if
        return(0);
    }
    /* }}} */


    /* {{{ function has_dirty( $str ) */
    /**
     * Check if a string has dirty words.
     *
     * @param   string  $str        String.
     * @return  string  0: no dirty word, other: the dirty word found.
     */
    function has_dirty( $str )
    {
        reset( $this->dirty_words );
        while ( list($key, $val) = each($this->dirty_words) ) {
            if ( function_exists( "iconv_strpos" ) ) {
                $ret = @iconv_strpos( $str, $key, 0, "UTF-8" );
            } else {
                $ret = strpos( $str, $key );
            } // if function_exists

            if ( $ret !== false
                    && ( ($val <= $this->lev) || 0 == $this->lev ) ) {
                return( $key );
            } // if
        } // while
        return(0);
    }
    /* }}} */

    /**
     * 替换脏字程序
     * 将匹配到的脏字替换为***字符串
     *
     * @param string $source 源字串
     * @param string $target 目标字串，使用引用传递值
     * @return 0/errorno
     */
    function replace_dirty($source,&$target)
    {
        $replace_str='***';
        $target=$source;
        foreach ($this->dirty_words as $key => $value)
        {
            $target=str_replace($key,$replace_str,$target);
        }
        return 0;
    }
}