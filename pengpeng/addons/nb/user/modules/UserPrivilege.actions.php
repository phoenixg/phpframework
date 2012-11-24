<?php
class UserPrivilegeActions extends nbAction
{
  public function indexAction()
  {
    $table = nbAppHelper::getCurrentAppConfig('table', __FILE__);
    $idColumn = nbAppHelper::getCurrentAppConfig('idColumn', __FILE__);
    $nameColumn = nbAppHelper::getCurrentAppConfig('nameColumn', __FILE__);

    $userTable = array(
      'name' => $table,
      'alias' => 'u',
      'field' => array(
        array('name' => $idColumn, 'alias' => 'uid'),
        array('name' => $nameColumn, 'alias' => 'username'),
      ),
      'groupBy' => array(
        'field' => $idColumn,
      )
    );

    $roleTable = array(
      'name' => 'cs_user_role',
      'alias' => 'ur',
      'field' => array(
        array('name' => 'role', 'alias' => 'role', 'function' => 'GROUP_CONCAT', 'distinct' => true),
      ),
    );

    $privilegeTable = array(
      'name' => 'cs_user_privilege',
      'alias' => 'up',
      'field' => array(
        array('name' => 'privilege', 'alias' => 'privilege', 'function' => 'GROUP_CONCAT', 'distinct' => true),
      ),
    );

    $builder = new nbQueryBuilder();
    $builder->addRelation($userTable, $roleTable, array('leftField' => $idColumn, 'rightField' => 'user_id'));
    $builder->addRelation($userTable, $privilegeTable, array('leftField' => $idColumn, 'rightField' => 'user_id'));

    $this->builder = $builder;
  }

  public function editUserPrivilegeAction()
  {
    $uid = $this->request->getGet('id');

    $table = nbAppHelper::getCurrentAppConfig('table', __FILE__);
    $idColumn = nbAppHelper::getCurrentAppConfig('idColumn', __FILE__);
    $nameColumn = nbAppHelper::getCurrentAppConfig('nameColumn', __FILE__);

    $username = $this->service->selectOne($nameColumn, $table, array($idColumn => $uid));

    $this->userRoles = nbUserHelper::getUserRolesBySeparateByAppName($uid);
    $this->userPrivileges = nbUserHelper::getUserPrivilegesBySeparateByAppName($uid);

    $this->allRoles = nbUserHelper::getAllRolesBySeparateByAppName();
    $this->allRolePrivileges = nbUserHelper::getAllRolePrivilegesSeparateByAppName();

    $this->apps = nbConfigHelper::getConfig('nb-user/item');

    $this->username = $username;
    $this->uid = $uid;
  }

  public function submitAction()
  {
    $uid = $this->request->getGet('uid');

    $privilege = array_unique($this->request->getPost('privilege', array()));
    $roles = array_unique($this->request->getPost('roles', array()));

    $this->service->delete('cs_user_role', array('user_id' => $uid));
    $this->service->delete('cs_user_privilege', array('user_id' => $uid));

    foreach ($roles as $role)
    {
      $this->service->insert('cs_user_role', array('user_id' => $uid, 'role' => $role));
    }

    foreach ($privilege as $onePrivilege)
    {
      $this->service->insert('cs_user_privilege', array('user_id' => $uid, 'privilege' => $onePrivilege));
    }

    $this->redirect('/nb-user.php/userPrivilege/editUserPrivilege?id='.$uid);
  }
}