<?php
/**
 * Copyright (c) 2009 3Guys, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF 3Guys, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

/**
 *
 * @author 3Guys
 *
 */
class FilterChain
{
  private static $instance;

  /**
   *
   * Enter description here...
   * @return FilterChain
   */
  public static function getInstance()
  {
    if (self::$instance == null)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   *
   * @return unknown_type
   */
  public function startFilterChain()
  {
    $this->filters = nbAppHelper::getCurrentAppConfig('cs-mvc/filters', __FILE__);
    $this->index = -1;

    $this->execute();
  }

  /**
   *
   * @return unknown_type
   */
  public function execute()
  {
    $this->index = $this->index + 1;
    if (isset($this->filters[$this->index]))
    {
      $filter = new $this->filters[$this->index];

      return $filter->execute($this);
    }
  }
}