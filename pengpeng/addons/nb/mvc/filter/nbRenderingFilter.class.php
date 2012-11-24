<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbRenderingFilter extends nbFilter
{
  public function execute($filterChain)
  {
    $filterChain->execute();

    $replace = "\n".nbResponse::getInstance()->renderHead()."\n</head>";
    echo str_replace("</head>", $replace, nbResponse::getInstance()->content);
  }
}