<?php

class TMUserService
{
  public static function getUserInfo($qq = '')
  {
    $qq = $qq ? $qq : TMAuthUtils::getUin();
    $dao = new TMUserDao;
    return $dao->getUserInfo($qq);
  }
}

?>
