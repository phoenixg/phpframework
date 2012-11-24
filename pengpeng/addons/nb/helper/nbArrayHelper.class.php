<?php
class nbArrayHelper
{
  // hack only support level 0-2
  public static function getArrayDeepLevel($values)
  {
    if (is_string($values))
    {
      return 0;
    }
    else if (is_array($values))
    {
      foreach ($values as $value)
      {
        if (is_array($value))
        {
          return 2;
        }
      }

      return 1;
    }
  }

  /*
   * $this->para = nbArrayHelper::bindPara($para, array(
            'root',
            'ignoreFolder',
            'ignorePath',
            'ignoreFile',
            'fileRegx',
            'pathRegx',
            'fileName'));
   */
  public static function bindPara($paraValue, $paraList, $defaultList = '')
  {
    foreach ($paraList as $key => $para)
    {
      if (!isset($paraValue[$para]))
      {
        $paraValue[$para] = $defaultList ? $defaultList[$key] : '';
      }
    }

    return $paraValue;
  }
}