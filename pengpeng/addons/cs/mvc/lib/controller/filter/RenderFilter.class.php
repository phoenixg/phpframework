<?php
/**
 * Copyright (c) 2009 3Guys, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF 3Guys, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

/**
 * this is a filterChain example
 * @author 3Guys
 *
 */
class RenderFilter
{
  /**
   *
   * @param FilterChain $filterChain
   * @return unknown_type
   */
  public function execute(FilterChain $filterChain)
  {
    $contents = $filterChain->execute();

    if (false !== ($pos = strpos($contents, '</head>')))
    {
      $response = nbResponse::getInstance();

      $str = "";
      $str .= '<meta http-equiv="content-type" content="';
      $str .= isset($response->meta['content-type']) ? $response->meta['content-type'] : 'text/html;charset=UTF-8' ;
      $str .= '" />'."\n";
      $str .= HeadHelper::renderTitle();

      $str .= HeadHelper::renderJs();
      $str .= HeadHelper::renderCss();

      //      foreach ($response->meta as $name => $meta)
      //      {
      //        if ($name != 'content-type')
      //        {
      //          $str .= '<meta name="'.$name.'" content="'.$meta.'" />'."\n";
      //        }
      //      }

      $contents = substr($contents, 0, $pos).$str.substr($contents, $pos);

      echo $contents;
    }
    else
    {
      echo $contents;
    }
  }
}