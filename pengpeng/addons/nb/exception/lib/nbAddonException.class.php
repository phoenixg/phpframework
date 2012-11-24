<?php
class nbAddonException extends nbException
{
  public function __construct($message, $code = 3)
  {
    parent::__construct($message, $code);
  }
}