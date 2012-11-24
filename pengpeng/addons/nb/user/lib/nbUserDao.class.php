<?php
class nbUserDao extends CsDao
{
  public function setTable()
  {
    $this->table = nbAppHelper::getCurrentAppConfig('table', __FILE__);
  }

  public function getUserInfoByUsername($username)
  {
    $nameColumn = nbAppHelper::getCurrentAppConfig('nameColumn', __FILE__);

    return $this->selectOne('*', $this->table, array($nameColumn => $username));
  }
}