<?php
class nbWidget
{
  public static function includePartial($path, array $parameters = array())
  {
    echo nbMvcWidget::getPartial($path, $parameters);
  }

  public static function getPartial($path, array $parameters = array())
  {
    return nbMvcHelper::executePartial($path, $parameters);
  }

  public static function includeComponent($path, array $parameters = array())
  {
    echo nbMvcWidget::getComponent($path, $parameters);
  }

  public static function getComponent($path, array $parameters = array())
  {
    return nbMvcWidget::getComponent($path, $parameters);
  }

  public static function linkTo($title, $path, $parameters = array())
  {
    return nbMvcWidget::linkTo($title, $path, $parameters);
  }

  public static function url($path, $query = '')
  {
    return nbMvcWidget::url($path, $query);
  }

  public static function truncate($value, $length)
  {
    return nbMvcWidget::truncate($value, $length);
  }
}