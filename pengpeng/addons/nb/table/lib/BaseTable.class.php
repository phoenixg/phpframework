<?php
abstract class BaseTable extends Table
{
  public function addOperate($id, $operates)
  {
    $html = '';
    foreach ($operates as $operate)
    {
      $html .= call_user_func(array($this, $operate.'Action'), $id). ' ';
    }

    return $html;
  }

  public function editAction($id)
  {
    $pathInfo = $this->getPathInfo();

    return HtmlHelper::linkTo(__('Edit'), $pathInfo['app'].'/'.$pathInfo['module'].'/edit', array('query' => "id=$id&caller=".getCaller()));
  }

  public function deleteAction($id)
  {
    $page = Request::getInstance()->getGet($this->getTableId().'[page]');

    $pathInfo = $this->getPathInfo();
    return HtmlHelper::linkTo(__('Delete'), $pathInfo['app'].'/'.$pathInfo['module'].'/delete', array('query' => 'id='.$id.'&page='.$page));
  }

  public function showAction($id)
  {
    $pathInfo = $this->getPathInfo();
    return HtmlHelper::linkTo(__('Show'), $pathInfo['app'].'/'.$pathInfo['module'].'/show', array('query' => 'id='.$id));
  }

  public function showImage($path)
  {
    return AssetHelper::imageTag($path);
  }

  public function showTime($time)
  {
    return date("n月j日 H点i分", strtotime($time));
  }

  protected function getPathInfo()
  {
    if ($path = Request::getInstance()->getGet('caller'))
    {
      $pathInfo = PathTool::readPath($path);
    }
    else
    {
      $pathInfo['app'] = APP;
      $pathInfo['module'] = Request::getInstance()->getModuleName();
      $pathInfo['action'] = Request::getInstance()->getActionName();
    }

    return $pathInfo;
  }
}