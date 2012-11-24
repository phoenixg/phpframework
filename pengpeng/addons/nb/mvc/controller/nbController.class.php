<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbController
{
  public function execute()
  {
    $filterChain = new nbFilterChain();
    $filterChain->execute();
  }
}