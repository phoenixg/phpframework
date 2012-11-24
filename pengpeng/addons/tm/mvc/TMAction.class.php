<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class TMAction extends nbAction
{
  public $service;
  public $cache;

  public function __construct()
  {
    $this->request = nbRequest::getInstance();
    $this->response = nbResponse::getInstance();
    $this->service = new TMService;
    $this->cache = TMMemCacheMgr::getInstance();
  }
}