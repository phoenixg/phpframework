<?php
class nbHtmlWidget
{
  public static function tag($type, array $options = array(), $content = false, $followContent = '')
  {
    $html = "<$type";
    foreach ($options as $name => $value)
    {
      $html .= " $name=\"$value\"";
    }

    if ($content !== false)
    {
      $html .= ">$content</$type>";
    }
    else
    {
      $html .= " />";
    }

    if ($followContent)
    {
      $html .= ' '.$followContent;
    }
//
//
//    if (in_array($type, array('a', 'textarea')))
//    {
//      $html = "<$type";
//      foreach ($options as $name => $value)
//      {
//        $html .= " $name=\"$value\"";
//      }
//      $html .= ">$content</$type>";
//    }
//    else if ('checkbox' == $options['type'])
//    {
//      $html = "<input";
//      foreach ($options as $name => $value)
//      {
//        $html .= " $name=\"$value\"";
//      }
//      $html .= " /> $content";
//    }
//    else if ('input' == $type)
//    {
//      $html = "<$type";
//      foreach ($options as $name => $value)
//      {
//        $html .= " $name=\"$value\"";
//      }
//      $html .= " />";
//    }

    return $html;
  }

  public static function formTag($type, array $options = array(), $content = '')
  {
    $html = "<input type=\"$type\"";
    foreach ($options as $name => $value)
    {
      $html .= " $name=\"$value\"";
    }
    $html .= " />";

    return $html;
  }
}