<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbLog
{
  public function __construct($message)
  {
    if (nbLogHelper::logAble())
    {
      $log = nbFactory::getInstance()->getFactory('nb-log');

      if (!$log)
      {
        nbFactory::getInstance()->setFactory('nb-log', $this);
        $this->writeBlankLine();
        $log = $this;
      }

      $log->writeLog($message);
    }
  }

  private function writeLog($message)
  {
    $cacheFile = nbAppHelper::getCurrentAppConfig('cacheFile', __FILE__);

    if (file_exists($cacheFile))
    {
      $content = file_get_contents($cacheFile);
    }
    else
    {
      $content = '';
    }

    $generate = new nbGenerate();
    $generate->writeFile($cacheFile, "$content\n".$this->getLineContent(debug_backtrace()));
  }

  private function writeBlankLine()
  {
    $cacheFile = nbAppHelper::getCurrentAppConfig('cacheFile', __FILE__);
    if (file_exists($cacheFile))
    {
      $content = file_get_contents($cacheFile);
    }
    else
    {
      $content = '';
    }

    $generate = new nbGenerate();
    $generate->writeFile($cacheFile, "$content\n");
  }

  private function getLineContent($traceInfo)
  {
    $format = nbAppHelper::getCurrentAppConfig('format', __FILE__);
    $formatValueFrom = nbAppHelper::getCurrentAppConfig('formatValueFrom', __FILE__);

    preg_match_all('/%\w+%/', $format, $matchs);
    foreach ($matchs[0] as $token)
    {
      if (!isset($formatValueFrom[$token]))
      {
        throw new nbCoreException("do not have the token value called '$token'");
      }

      $tokenValue = call_user_func($formatValueFrom[$token], array($traceInfo));
      $format = str_replace($token, $tokenValue, $format);
    }

    return $format;
  }
}