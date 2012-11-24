<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbException extends Exception
{
  public function __construct ($message, $code)
  {
    $filePath = $this->getFile();
    $appName = nbAppHelper::getAppNameByFilePath($filePath);

    parent::__construct("[$appName]" . $message, $code);
  }
}