<?php
class RoleComponents extends nbAction
{
  /**
   * use section here called "includeSection"
   *
   * @return multitype:number
   */
  public function getRoleNameAction()
  {
    $roles = CsUserHelper::getAllRoles();

    foreach ($roles as $role => $roleInfo)
    {
      $options[$role] = $roleInfo['name'];
    }

    return $options;
  }

  public function getUserRolesAction()
  {
    $roles = CsUserHelper::getAllRoles();

    foreach ($roles as $role => $roleInfo)
    {
      $options[$role] = $roleInfo['name'];
    }

    return $options;
  }
}