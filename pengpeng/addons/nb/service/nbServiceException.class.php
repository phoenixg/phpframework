<?php
class nbMessageException extends CoreException
{
  public function __construct($message, $code = 1)
  {
    parent::__construct($message, $code);
  }
}