<?php
class TMCodeService
{
  private static $codeInfo;

  public static function haveNoUsedCode($code)
  {
    if (!self::$codeInfo)
    {
      self::getCodeInfo();
    }

    if (self::$codeInfo && self::$codeInfo['FStatus'] == 0)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public static function getCodeInfo($code)
  {
    $dao = new TMCodeDao;
    self::$codeInfo = $dao->getCodeInfo($code);
  }

  public static function haveCode($code)
  {
    if (!self::$codeInfo)
    {
      self::getCodeInfo($code);
    }

    if (!self::$codeInfo)
    {
      return false;
    }
    else
    {
      return true;
    }
  }

  public static function useCode($code)
  {
    $dao = new TMCodeDao;
    $dao->useCode($code);
  }
}