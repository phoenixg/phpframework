<?php
class UserActions extends nbAction
{
  public function indexAction()
  {
    $table = nbAppHelper::getCurrentAppConfig('table', __FILE__);
    $idColumn = nbAppHelper::getCurrentAppConfig('idColumn', __FILE__);
    $nameColumn = nbAppHelper::getCurrentAppConfig('nameColumn', __FILE__);

    $table = array(
      'name' => $table,
      'field' => '*',
      'orderBy' => array('field' => $nameColumn, 'type' => 'desc'),
    );

    $builder = new nbQueryBuilder();
    $builder->addTable($table);

    $this->builder = $builder;
  }

  public function editAction()
  {
    $id = $this->request->getGet('id');

    $this->editValue = $this->service->selectOne('*', 'cs_user', array('id' => $id));

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
    $username = $this->request->getPost('username');
    $oldPassword = $this->request->getPost('oldPassword');
    $newPassword = $this->request->getPost('newPassword');

    // TODO check the old password whether is correct

    $this->service->update('cs_user', array('username' => $username, 'password' => $newPassword), array('id' => $id));

    $this->redirect('/cs-user.php/user/index');
  }

  private function insertSubmit()
  {
    $username = $this->request->getPost('username');
    $oldPassword = $this->request->getPost('oldPassword');
    $newPassword = $this->request->getPost('newPassword');

    // TODO check the old password whether is correct
    $this->service->insert('cs_user', array('username' => $username, 'password' => $newPassword));

    $this->redirect('/cs-user.php/user/index');
  }

  public function addAction()
  {

  }

  public function deleteAction()
  {
    $id = $this->request->getGet('id');

    $this->service->delete('cs_user', array('id' => $id));

    $this->redirect('/cs-user.php/user/index');
  }
}