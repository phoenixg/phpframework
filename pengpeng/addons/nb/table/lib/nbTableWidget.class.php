<?php
class nbTableWidget
{
  public static function operate($id)
  {
    $editLink = nbWidget::linkTo('编辑', 'edit', array('query' => "id=$id"));
    $deleteLink = nbWidget::linkTo('删除', 'delete', array('query' => "id=$id"));
    return $editLink . ' ' .$deleteLink;
  }

  public static function display($value)
  {
    return $value;
  }
}