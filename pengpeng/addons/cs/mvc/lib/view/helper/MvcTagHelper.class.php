<?php
class MvcTagHelper
{
  public static function tag($type, array $options = array(), $content = '')
  {
    if (in_array($type, array('a', 'textarea')))
    {
      $html = "<$type";
      foreach ($options as $name => $value)
      {
        $html .= " $name=\"$value\"";
      }
      $html .= ">$content</$type>";
    }
    else if ('checkbox' == $options['type'])
    {
      $html = "<input";
      foreach ($options as $name => $value)
      {
        $html .= " $name=\"$value\"";
      }
      $html .= " /> $content";
    }
    else if ('input' == $type)
    {
      $html = "<$type";
      foreach ($options as $name => $value)
      {
        $html .= " $name=\"$value\"";
      }
      $html .= " />";
    }

    return $html;
  }
}