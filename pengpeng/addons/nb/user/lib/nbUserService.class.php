<?php
class nbUserService
{
  public static $loginUserInfo = array();

  public static function getPasswordByUsername($username)
  {
    $dao = new nbUserDao;
    $passwordColumn = nbAppHelper::getCurrentAppConfig('passwordColumn', __FILE__);

    $userInfo = $dao->getUserInfoByUsername($username);
    return $userInfo[$passwordColumn];
  }
}