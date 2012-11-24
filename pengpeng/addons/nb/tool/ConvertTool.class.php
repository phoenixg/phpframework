<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class ConvertTool
{
  /**
   *
   * @param string $value
   * @return string
   */
  public static function toPascal($value)
  {
    if (!$value)
    {
      return '';
    }

    return ucfirst(self::toCamel($value));
  }

  /**
   *
   * @param string $value
   * @return string
   */
  public static function toCamel($value)
  {
    $value = preg_replace('/ /', '', $value);

    // only PHP 5 >= 5.3.0 have lcfirst function
    if (true === function_exists('lcfirst'))
    {
      return lcfirst(preg_replace('/_(\w)/e', "strtoupper('$1')", $value));
    }
    else
    {
      return self::lcfirst(preg_replace('/_(\w)/e', "strtoupper('$1')", $value));
    }
  }

  /**
   *
   * @param string $value
   * @return string
   */
  public static function toDisplay($value)
  {
    return preg_replace('/(\w)([A-Z])/', "$1 $2", self::toPascal($value));
  }

  /**
   * make the first letter lower
   *
   * @param string $value
   * @return string
   */
  public static function lcfirst($value)
  {
    if (!$value)
    {
      return $value;
    }
    $value{0} = strtolower($value{0});

    return $value;
  }

  /**
   *
   * @param string $name
   * @param string $value
   * @return string
   */
  public static function nameToId($name, $value = null)
  {
    // check to see if we have an array variable for a field name
    if (strstr($name, '['))
    {
      $name = str_replace(array('[]', '][', '[', ']'), array((($value != null) ? '_'.$value : ''), '_', '_', ''), $name);
    }

    return $name;
  }

  /**
   *
   * @param string $value
   * @return string
   */
  public static function toUnderline($value)
  {
    return preg_replace('/[^\w]/', '_', $value);
  }

  public static function toPath($value)
  {
    return str_replace('-', '/', $value);
  }
}