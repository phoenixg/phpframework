<?php
class nbUserPrivilegeTableWidget
{
  public static function getUserPrivilege($privilege)
  {
    if (strstr($privilege, '/'))
    {
      $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);
      list($app, $role) = explode('/', $privilege);

      return $items[$app]['privilege'][$role]['name'];
    }
  }

  public static function getUserPrivilegeForGroup($privilege)
  {
    $privileges = explode(',', $privilege);
    foreach ($privileges as $privilege)
    {
      $privilegeArray[] = self::getUserPrivilege($privilege);
    }

    return implode(', ', $privilegeArray);
  }
}