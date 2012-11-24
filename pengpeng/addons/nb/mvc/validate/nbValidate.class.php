<?php
class nbValidate
{
  public static function dealStrategy($value, $strategyName)
  {
    $strategies = nbAppHelper::getCurrentAppConfig('strategies', __FILE__);
    $strategy = $strategies[$strategyName];
    if (!isset($strategy['para']) || !$strategy['para'])
    {
      $strategy['para'] = array();
    }

    if (call_user_func_array($strategy['method'], array($value, $strategy['para'])))
    {
      return true;
    }
    else
    {
      if (isset($strategy['exception']) && $strategy['exception'])
      {
        throw new nbAddonException($strategy['exception']);
      }
      else
      {
        return false;
      }
    }
  }

  public static function haveValue($value)
  {
    return !empty($value);
  }

  public static function isInt($value)
  {
    return is_int($value);
  }

  public static function inArray($value, $array)
  {
    return in_array($value, $array);
  }
}