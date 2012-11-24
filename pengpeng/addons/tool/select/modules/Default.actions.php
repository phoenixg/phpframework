<?php
class DefaultActions extends nbAction
{
  public function indexAction()
  {
    $mark = $this->request->getPost('mark');
    $tblName = $this->request->getPost('tblname');
    $where = $this->request->getPost('condition');
    $group = $this->request->getPost('group');
    $order = $this->request->getPost('order');
    $limit = $this->request->getPost('limit');
    if ($order)
    {
      $sql = "SELECT $mark FROM $tblName  ORDER BY $order";
    } 
    elseif ($mark && $tblName && $where)
    {
      $sql = "SELECT $mark FROM $tblName WHERE $where";
    }
    elseif ($mark && $tblName)
    {
      $sql = "SELECT $mark FROM $tblName";
    }
    elseif ($mark && $tblName && $where && $group)
    {
      $sql = "SELECT $mark FROM $tblName WHERE $where GROUP BY $group";
    }
    if ($limit)
    {
      $sql = "SELECT $mark FROM $tblName LIMIT $limit";
    }
	if($this->request->getPost('submit'))
	{
		$this->array = $this->service->query($sql);
		$this->chars = array_keys($this->array[0]); 
	}
    
  }
}
