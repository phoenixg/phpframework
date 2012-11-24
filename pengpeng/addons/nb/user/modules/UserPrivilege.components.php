<?php
class UserPrivilegeComponents extends nbAction
{
  public function getUserPrivilegesAction()
  {
   $privileges = CsUserHelper::getAllPrivileges();

    foreach ($privileges as $privilege => $privilegeInfo)
    {
      $options[$privilege] = $privilegeInfo['name'];
    }

    return $options;
  }
}