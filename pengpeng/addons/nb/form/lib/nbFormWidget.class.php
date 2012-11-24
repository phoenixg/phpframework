<?php
class nbFormWidget
{
  public static function text($name, $value = '', $attribute = array())
  {
    $attribute['type'] = 'text';
    $attribute['name'] = $name;
    if ($value)
    {
      $attribute['value'] = $value;
    }

    return nbHtmlWidget::tag('input', $attribute);
  }

  public static function textarea($name, $value = '', $attribute = array())
  {
    $attribute['name'] = $name;
    if (isset($attribute['size']))
    {
      list($cols, $rows) = explode('x', $attribute['size']);
      $attribute['cols'] = $cols;
      $attribute['rows'] = $rows;
      unset($attribute['size']);
    }

    return nbHtmlWidget::tag('textarea', $attribute, $value);
  }

  public static function password($name, $value = '', $attribute = array())
  {
    $attribute['type'] = 'password';
    $attribute['name'] = $name;
    if ($value)
    {
      $attribute['value'] = $value;
    }

    return nbHtmlWidget::tag('input', $attribute);
  }

  public static function submit($name = '', $attribute = array())
  {
    $attribute['type'] = 'submit';

    return nbHtmlWidget::tag('input', $attribute);
  }

  public static function hidden($name, $value = '', $attribute = array())
  {
    $attribute['type'] = 'hidden';
    $attribute['name'] = $name;
    if ($value)
    {
      $attribute['value'] = $value;
    }

    return nbHtmlWidget::tag('input', $attribute);
  }

  public static function checkbox($name, $value, $checked = false, $followContent = '', $attribute = array())
  {
    $attribute['type'] = 'checkbox';
    $attribute['name'] = $name.'[]';
    $attribute['value'] = $value;
    if ($checked)
    {
      $attribute['checked'] = 'checked';
    }

    return nbHtmlWidget::tag('input', $attribute, '', $followContent);
  }

  /**
   * $selectOptions like array($arrayOptions, $value)
   */
  public static function select($name, $value, $selectOptions, $attribute = array())
  {
    $attribute['name'] = $name;

    return nbHtmlWidget::tag('select', $attribute, self::getSelectOptions($selectOptions, $value));
  }

  public static function pathSelect($name, $value = '', $path, $attribute = array())
  {
    $attribute['name'] = $name;

    $options = nbWidget::getComponent($path);

    return nbHtmlWidget::tag('select', $attribute, self::getSelectOptions($options, $value));
  }

  public static function pathSelectMany($name, $value = array(), $path, $attribute = array())
  {
    $attribute['name'] = $name.'[]';
    $attribute['multiple'] = 'multiple';

    $options = nbWidget::getComponent($path);

    return nbHtmlWidget::tag('select', $attribute, self::getSelectManyOptions($options, $value));
  }

  private static function getSelectOptions($options, $value = '', $attribute = array())
  {
    $optionString = "\n";
    foreach ($options as $optionKey => $optionValue)
    {
      $attribute['value'] = $optionKey;
      if ($optionKey == $value)
      {
        $attribute['selected'] = 'selected';
      }

      $optionString .= nbHtmlWidget::tag('option', $attribute, $optionValue)."\n";
      unset($attribute['selected']);
    }

    return $optionString;
  }

  private static function getSelectManyOptions($options, $value = array(), $attribute = array())
  {
    $optionString = "\n";
    foreach ($options as $optionKey => $optionValue)
    {
      $attribute['value'] = $optionKey;
      if (in_array($optionKey, $value))
      {
        $attribute['selected'] = 'selected';
      }

      $optionString .= nbHtmlWidget::tag('option', $attribute, $optionValue)."\n";
      unset($attribute['selected']);
    }

    return $optionString;
  }

  public static function display($value)
  {
    return $value;
  }
}