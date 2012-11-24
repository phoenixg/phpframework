<?php
class CsFilterDebugComponents extends nbAction
{
  public function indexAction()
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);

    foreach ($items as $item)
    {
      $name = $item['name'];
      $this->debugInfo[$name]['name'] = $name;
      $this->debugInfo[$name]['image'] = $item['image'];
      $this->debugInfo[$name]['content'] = nbWidget::getComponent($item['path']);
    }
  }
}