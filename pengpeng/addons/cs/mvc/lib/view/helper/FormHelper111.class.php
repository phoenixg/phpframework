<?php
class FormHelper111
{
  public static function text($name, $value = null, $options = array())
  {
    $defaultOption = array('type' => 'text',
      'name' => $name,
      'id' => ConvertTool::nameToId($name, $value),
      'value' => $value
    );

    return TagHelper::tag('input', array_merge($defaultOption, $options));
  }

  public static function textarea($name, $value = null, $options = array())
  {
    $defaultOption = array(  'name' => $name,
      'width' => '200',
      'id' => ConvertTool::nameToId($name));

    return TagHelper::tag('textarea', array_merge($defaultOption, $options), $value);
  }

  public static function submit($name, $value = null, array $options = array())
  {
    if ($value)
    {
      $defaultOption = array('type' => 'submit', 'value' => $value);
    }
    else
    {
      $defaultOption = array('type' => 'submit');
    }

    return TagHelper::tag('input', array_merge($defaultOption, $options));
  }

  public static function hidden($name, $value = null, $options = array())
  {
    $defaultOption = array('type' => 'hidden',
      'name' => $name,
      'id' => ConvertTool::nameToId($name, $value),
      'value' => $value
    );

    return TagHelper::tag('input', array_merge($defaultOption, $options));
  }
}