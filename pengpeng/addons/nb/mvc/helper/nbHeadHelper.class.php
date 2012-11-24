<?php
class nbHeadHelper
{
  /**
   *
   * @param string $values
   * @param array $options
   * @return void
   */
  public static function useJs($value, array $options = array())
  {
    if (isset($options['position']) && $options['position'] == 'first')
    {
      nbData::addFirst('js', $value, 'html-head-value');
    }
    else
    {
      nbData::add('js', $value, 'html-head-value');
    }
  }

  /**
   *
   * @param array $values
   *
   * @return void
   */
  public static function useCss($values, array $options = array())
  {
    if (isset($options['position']) && $options['position'] == 'first')
    {
      nbData::addFirst('css', $value, 'html-head-value');
    }
    else
    {
      nbData::add('css', $value, 'html-head-value');
    }
  }

  /**
   *
   * @param string $name
   * @return void
   */
  public static function setTitle($value)
  {
    return nbData::set('title', $value, 'html-head-value');
  }

  /**
   *
   * @return string
   */
  public static function getTitle()
  {
    return nbData::get('title', 'html-head-value');
  }

  public static function setBase($value)
  {
    return nbData::set('base', $value, 'html-head-value');
  }

  /**
   *
   * @param string $name
   * @return void
   */
  public static function useMeta(array $name)
  {
    if (isset($options['position']) && $options['position'] == 'first')
    {
      nbData::addFirst('meta', $value, 'html-head-value');
    }
    else
    {
      nbData::add('meta', $value, 'html-head-value');
    }
  }
}