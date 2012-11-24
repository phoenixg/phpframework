<?php
class nbUserRoleTableWidget
{
  public static function getUserRole($role)
  {
    if (strstr($role, '/'))
    {
      $roles = nbAppHelper::getCurrentAppConfig('item', __FILE__);
      list($app, $role) = explode('/', $role);

      return $roles[$app]['role'][$role]['name'];
    }
  }

  public static function getUserRoleForGroup($role)
  {
    $roles = explode(',', $role);
    foreach ($roles as $role)
    {
      $roleArray[] = self::getUserRole($role);
    }

    return implode(', ', $roleArray);
  }
}