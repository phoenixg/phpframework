<?php
class nbUserHelper
{
  public static function havaPrivilege($uid, $privilegeName)
  {
    $service = new nbService;
    $userRoles = $service->selectColumn('role', 'Cs_User_Role', array('user_id' => $uid));
    $userPrivileges = $service->selectColumn('privilege', 'Cs_User_Privilege', array('user_id' => $uid));
    $userPrivileges = self::getPrivilegeByRoles($userRoles) + $userPrivileges;

    return in_array($privilegeName, $userPrivileges);
  }

  public static function getPrivilegeByRoles($userRoles)
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);

    $return = array();
    foreach ($userRoles as $role)
    {
      list($app, $roleName) = explode('/', $role);
      foreach ($items[$app]['rolePrivilege'][$roleName] as $privilege)
      {
        $return[] = $app.'/'.$privilege;
      }
    }
    return $return;
  }

  public static function addRole($uid, $roleName)
  {
    $service = new nbService;
    $service->insert('Cs_User_Role', array('user_id' => $uid, 'role' => $roleName));
  }

  public static function addPrivilege($uid, $privilegeName)
  {
    $service = new nbService;
    $service->insert('CS_User_Privilege', array('user_id' => $uid, 'privilege' => $privilegeName));
  }

  public static function getAllRoles()
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    foreach ($items as $app => $item)
    {
      foreach ($item['role'] as $roleKey => $roleInfo)
      {
        $allRoles[$app.'/'.$roleKey] = $roleInfo;
      }
    }

    return $allRoles;
  }

  public static function getAllPrivileges()
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    $allPrivileges = array();
    foreach ($items as $app => $item)
    {
      if (isset($item['privilege']))
      {
        foreach ($item['privilege'] as $privilegeKey => $privilegeInfo)
        {
          $allPrivileges[$app.'/'.$privilegeKey] = $privilegeInfo;
        }
      }
    }

    return $allPrivileges;
  }

  public static function getAllRolesByAppName($appName)
  {

    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    $allRoles = array();
    foreach ($items as $app => $item)
    {
      if ($app == $appName)
      {
        foreach ($item['role'] as $roleKey => $roleInfo)
        {
          $allRoles[$app.'/'.$roleKey] = $roleInfo;
        }
      }
    }

    return $allRoles;
  }

  public static function getUserRolesBySeparateByAppName($uid)
  {
    $service = new nbService();
    $roles = $service->select('role', 'cs_user_role', array('user_id' => $uid));

    $userRoles = array();
    foreach ($roles as $role)
    {
      list($app, $roleName) = explode('/', $role['role']);
      $userRoles[$app][] = $role['role'];
    }

    return $userRoles;
  }

  public static function getUserPrivilegesBySeparateByAppName($uid)
  {
    $service = new nbService();
    $privileges = $service->select('privilege', 'cs_user_privilege', array('user_id' => $uid));

    $userPrivileges = array();
    foreach ($privileges as $privilege)
    {
      list($app, $roleName) = explode('/', $privilege['privilege']);
      $userPrivileges[$app][] = $privilege['privilege'];
    }

    return $userPrivileges;
  }

  public static function getAllRolesBySeparateByAppName()
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);

    foreach ($items as $app => $item)
    {
      foreach ($item['role'] as $roleKey => $role)
      {
        $allRoles[$app][$app.'/'.$roleKey] = array(
          'name' => $role['name'],
          'description' => isset($role['description']) ? $role['description'] : '',
        );
      }
    }

    return $allRoles;
  }

  public function getAllRolePrivilegesSeparateByAppName()
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);

    foreach ($items as $app => $item)
    {
      if (isset($item['rolePrivilege']))
      {
        foreach ($item['rolePrivilege'] as $role => $privilege)
        {
          foreach ($privilege as $privilegeKey => $privilege)
          {
            if (isset($item['privilege']))
            {
              $allPrivilege[$app][$app.'/'.$role][$app.'/'.$privilege] = array(
                'name' => $item['privilege'][$privilege]['name'],
                'description' => isset($item['privilege'][$privilege]['description']) ? $item['privilege'][$privilege]['description'] : '',
              );
            }
          }
        }
      }
    }

    return $allPrivilege;
  }

  public function isCorrectPasswordByUsername($username, $password)
  {
    $passwordEscapeMethod = nbAppHelper::getCurrentAppConfig('passwordEscapeMethod', __FILE__);
    $passwordHashValue = call_user_func('nbUserHelper::md5', $password);

    $dbPassword = nbUserService::getPasswordByUsername($username);
    return $dbPassword == $passwordHashValue;
  }

  public function isLogin()
  {
    $loginCookieName = nbAppHelper::getCurrentAppConfig('loginCookieName', __FILE__);
    $loginCookieValue = nbRequest::getInstance()->getCookie($loginCookieName);
    if (!session_id())
    {
      session_start();
    }
    if (!isset($_SESSION[$loginCookieName]))
    {
      return false;
    }
    return $_SESSION[$loginCookieName] == $loginCookieValue;
  }

  public function setLoginFlagCookie($expire = 0)
  {
    $loginCookieName = nbAppHelper::getCurrentAppConfig('loginCookieName', __FILE__);
    $loginCookieValue = substr(md5(rand(0, 10000000).time().$loginCookieName), 10, 10);
    nbResponse::getInstance()->setCookie($loginCookieName, $loginCookieValue);
    if (!session_id())
    {
      session_start();
    }
    $_SESSION[$loginCookieName] = $loginCookieValue;
    //$_SESSION['loginUserId'] = $loginCookieValue;
  }

  public function delLoginFlagCookie($expire = 0)
  {
    $loginCookieName = nbAppHelper::getCurrentAppConfig('loginCookieName', __FILE__);

    nbResponse::getInstance()->delCookie($loginCookieName);
    if (!session_id())
    {
      session_start();
    }
    unset($_SESSION[$loginCookieName]);
  }

  public static function md5($password)
  {
    return md5($password);
  }
}