<?php

class Phxexception extends Exception
{
    public function __construct()
    {
    	parent::__construct();
    }

    public function getMsg()
    {
    	$message = $this->getLine() . $this->getMessage();
    	return $message;
    }
}
