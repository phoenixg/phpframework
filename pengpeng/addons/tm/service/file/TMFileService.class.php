<?php
class TMFileService
{
  public static function getLatest($number, $page = 1)
  {
    $dao = new TMFileDao;
    return $dao->getLatest($number, $page);
  }

  public static function getHotest($number, $page = 1)
  {
    $dao = new TMFileDao;
    return $dao->getHotest($number, $page);
  }

  public static function getByQQ($qq, $number, $page = 1)
  {
    $dao = new TMFileDao;
    return $dao->getByQQ($qq, $number, $page);
  }
}