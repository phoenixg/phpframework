<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbData
{
  private static $value;

  public static function get($name, $nameSpace = 'default', $defaultValue = array())
  {
    if (isset(self::$value[$nameSpace][$name]))
    {
      return self::$value[$nameSpace][$name];
    }
    else
    {
      return $defaultValue;
    }
  }

  public static function has($name, $nameSpace = 'default')
  {
    return isset(self::$value[$nameSpace]) && isset(self::$value[$nameSpace][$name]);
  }

  public static function set($name, $value, $nameSpace = 'default')
  {
    return self::$value[$nameSpace][$name] = $value;
  }

  public static function getAll($nameSpace = 'default')
  {
    if (isset(self::$value[$nameSpace]))
    {
      return self::$value[$nameSpace];
    }
    else
    {
      return array();
    }
  }

  public static function clear($nameSpace = 'default')
  {
    self::$value[$nameSpace] = '';
  }

  public static function add($name, $value, $nameSpace = 'default')
  {
    self::$value[$nameSpace][$name][] = $value;
  }

  public static function addFirst($name, $value, $nameSpace = 'default')
  {
    array_unshift(self::$value[$nameSpace][$name], $value);
  }

  public static function addLast($value, $nameSpace = 'default')
  {
    self::$value[$nameSpace]['last'] = $value;
  }
}