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
 * File system
 *
 * @package lib.file
 * @author  Salon Zhao <salonzhao@tencent.com>
 * @version 2008-10-8
 */
class TMFile
{
    /**
     * @var resource    the file resource
     *
     * @access private
     */
    private $file;

    /**
     * @var string    the file path
     *
     * @access private
     */
    private $path;

    /**
     * Open the file, create it if the file is not exist
     *
     * @param  string $path     the path of the file
     * @param  string $mode     wb+, default, for writing,the cursor will be set at the begin
     *                             ab+,     for writing, the cursor will be set at the end
     *                             r,  read only
     * @throws TMFileException
     */
    public function __construct($path, $mode = "wb+")
    {
        $this->file = fopen ( $path, $mode );
        if (! $this->file) {
            throw new TMFileException ( "Failed to open the file!" . $path );
        }
        $this->path = $path;
    }

    /**
     * destruct
     * @access public
     */
    public function __destruct()
    {
        fclose ( $this->file );
    }

    /**
     * Get all contents of current file
     *
     * @return string $content
     */
    public function getAllContents()
    {
        $content = file_get_contents ( $this->path );
        return $content;
    }

    /**
     * get the file as binary stream. Caution, you need initialize your TMFile object with "b" option.
     * For exampe, new TMFile("/tmp/demo.jpg","rb")
     *
     * @return string $binaryStream
     */
    public function getBinaryStream()
    {
        $binaryStream = fread($this->file,filesize($this->path));
        return $binaryStream;
    }

    /**
     * insert content into file
     *
     * @access public
     * @param  string $line     the line string
     * @return void
     * @throws TMFileException
     */
    public function insert($line)
    {
        if (! fwrite ( $this->file, $line ))
        {
            throw new TMFileException ( "Failed to insert into this file, please check the privilege." );
        }
    }

}