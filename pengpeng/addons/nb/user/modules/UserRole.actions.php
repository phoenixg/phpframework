<?php
class UserRoleActions extends nbAction
{
  public function indexAction()
  {
    $userTable = array(
      'name' => 'cs_user_role',
      'alias' => 'ur',
      'field' => array(
        array('name' => 'id', 'alias' => 'urid'),
        array('name' => 'user_id'),
        array('name' => 'role'),
      ),
      'orderBy' => array('field' => 'id', 'type' => 'asc'),
    );

    $roleTable = array(
      'name' => 'cs_user',
      'alias' => 'u',
      'field' => array(
        array('name' => 'username'),
      ),
    );

    $builder = new nbQueryBuilder();
    $builder->addTable($userTable);
    $builder->leftJoin($roleTable, array('leftField' => 'user_id', 'rightField' => 'id'));

    $this->builder = $builder;
  }

  public function editAction()
  {
    $id = $this->request->getGet('id');

    $this->editValue = $this->service->select('*', 'cs_user_role', array('id' => $id));
  }

  public function submitAction()
  {
    $id = $this->request->getPost('id');
    if ($id)
    {
      $this->updateSubmit($id);
    }
    else
    {
      $this->insertSubmit();
    }
  }

  private function updateSubmit($id)
  {
    $id = $this->request->getPost('id');
    $rolename = $this->request->getPost('rolename');
    $uid = $this->request->getPost('uid');

    $updateValue = array(
      'role' => $rolename,
      'user_id' => $uid,
    );

    $this->service->update('cs_user_role', $updateValue, array('id' => $id));

    $this->redirect('/cs-user.php/userRole/index');
  }

  private function insertSubmit()
  {
    $uid = $this->request->getPost('uid');
    $rolename = $this->request->getPost('rolename');

    // TODO check the old password whether is correct

    $this->service->insert('cs_user_role', array('user_id' => $uid, 'role' => $rolename));

    $this->redirect('/cs-user.php/userRole/index');
  }

  public function addAction()
  {

  }

  public function deleteAction()
  {
    $id = $this->request->getGet('id');

    $this->service->delete('cs_user_role', array('id' => $id));

    $this->redirect('/cs-user.php/userRole/index');
  }

  public function editUserRolesAction()
  {
    $id = $this->request->getGet('id');

    $userRoles = $this->service->select('*', 'cs_user_role', array('user_id' => $id));
    $username = $this->service->selectOne('username', 'cs_user', array('id' => $id));
    foreach ($userRoles as $roles)
    {
      $roleIds[] = $roles['role'];
    }

    $this->editValue = array('roles' => implode(', ', $roleIds), 'user_id' => $id, 'username' => $username);
  }

  public function submitUserRolesAction()
  {
    $uid = $this->request->getPost('uid');
    $roles = $this->request->getPost('roles');

    $this->service->delete('cs_user_role', array('user_id' => $uid));
    foreach ($roles as $role)
    {
      $this->service->insert('cs_user_role', array('user_id' => $uid, 'role' => $role));
    }

    $this->redirect('/cs-user.php/userRole/index');
  }
}