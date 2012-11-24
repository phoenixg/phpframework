<?php
class TMAlertHelper
{
  public static function set($value)
  {
    nbData::set('alert', $value, 'tm-filter-alert');
  }

  public static function has()
  {
    return nbData::has('alert', 'tm-filter-alert');
  }

  public static function get()
  {
    return nbData::get('alert', 'tm-filter-alert');
  }
}