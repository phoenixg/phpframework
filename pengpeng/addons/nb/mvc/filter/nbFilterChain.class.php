<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbFilterChain
{
  public function execute()
  {
    $this->chain = nbAppHelper::getCurrentAppConfig('filter', __FILE__);
    ++$this->index;

    if ($this->index <= count($this->chain))
    {
      $className = $this->chain[$this->index - 1];
      $filter = new $className();
      $filter->execute($this);
    }
  }
}