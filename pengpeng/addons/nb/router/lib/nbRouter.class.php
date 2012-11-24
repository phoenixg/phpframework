<?php
class nbRouter
{
  public static function getRouter($name)
  {
    $items = nbAppHelper::getCurrentAppConfig('item', __FILE__);

    if (isset($items[$name]))
    {
      return $items[$name];
    }
    else
    {
      throw new nbCoreException("do not have a touter called $name");
    }
  }
}