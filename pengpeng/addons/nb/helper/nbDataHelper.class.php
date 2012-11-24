<?php
class nbDataHelper
{
  public static function bindPara($paraValue, $paraList)
  {
    foreach ($paraList as $para)
    {
      if (!isset($paraValue[$para]))
      {
        $paraValue[$para] = '';
      }
    }

    return $paraValue;
  }
}