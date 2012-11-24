<?php
class nb404Exception extends nbException
{
  public function __construct($message, $code = 3)
  {
    header("HTTP/1.1 404 Not Found");
    parent::__construct("[framework]" . $message, $code);
  }
}