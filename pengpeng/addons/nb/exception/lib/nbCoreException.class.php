<?php
class nbCoreException extends nbException
{
  public function __construct($message, $code = 2)
  {
    if (is_array($message))
    {
      $messageValue = $message[0];
      $query['message'] = $message[0];
      if (isset($message[1]))
      {
        foreach ((array)$message[1] as $key => $value)
        {
          $query['p'][$key] = $value;
          $messageValue = str_replace("%$key%", $value, $messageValue);
        }
      }
    }
    else
    {
      $query['message'] = $message;
      $messageValue = $message;
    }
    //p($query);
    //p(http_build_query($query));
    $message = nbWidget::linkTo($messageValue, 'nb-exception/display/index', array('query' => http_build_query($query)));
    parent::__construct($message, $code);
  }
}