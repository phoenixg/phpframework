<?php
class nbToolHelper
{
  public static function getIp()
  {
    if (isset ( $_SERVER ['HTTP_CLIENT_IP'] ) and ! empty ( $_SERVER ['HTTP_CLIENT_IP'] ))
    {
      return $_SERVER ['HTTP_CLIENT_IP'];
    }

    if (isset ( $_SERVER ['HTTP_PROXY_USER'] ) and ! empty ( $_SERVER ['HTTP_PROXY_USER'] ))
    {
      return $_SERVER ['HTTP_PROXY_USER'];
    }
    if (isset ( $_SERVER ['REMOTE_ADDR'] ) and ! empty ( $_SERVER ['REMOTE_ADDR'] ))
    {
      return $_SERVER ['REMOTE_ADDR'];
    }
    else
    {
      return "0.0.0.0";
    }
  }
}