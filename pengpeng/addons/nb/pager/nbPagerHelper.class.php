<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

class nbPagerHelper
{
  /**
   *
   * @param int $rowsTotal
   * @param int $rowsPerPage
   * @param int $atPage
   * @param int $displayPageAreaNum
   * @return array
   */
  public static function getPagerInfo($rowsTotal, $atPage = 1, $rowsPerPage = 10, $displayPageAreaNum = 5)
  {
    $pageInfo['rowsTotal'] = $rowsTotal;

    $pageInfo['pageTotal'] = ceil($rowsTotal / $rowsPerPage);
    $pageInfo['pageAt'] = $atPage;

    if ($pageInfo['pageTotal'] <= $displayPageAreaNum)
    {
      $pageInfo['pageStart'] = 1;
      $pageInfo['pageEnd'] = $pageInfo['pageTotal'] ? $pageInfo['pageTotal'] : 1;
    }
    else if ($atPage - ceil($displayPageAreaNum / 2) <= 0)
    {
      $pageInfo['pageStart'] = 1;
      $pageInfo['pageEnd'] = $displayPageAreaNum;
    }
    else if ($pageInfo['pageTotal'] - $atPage <= ceil($displayPageAreaNum / 2))
    {
      $pageInfo['pageStart'] = $pageInfo['pageTotal'] - $displayPageAreaNum + 1;
      $pageInfo['pageEnd'] = $pageInfo['pageTotal'];
    }
    else
    {
      $pageInfo['pageStart'] = $atPage - ceil($displayPageAreaNum / 2) + 1;
      $pageInfo['pageEnd'] = $pageInfo['pageStart'] + $displayPageAreaNum - 1;
    }

    $pageInfo['pageArea'] = range($pageInfo['pageStart'], $pageInfo['pageEnd']);

    $pageInfo['pageAtFirst'] = $pageInfo['pageAt'] == 1 ? true : false;
    $pageInfo['pageAtLast'] = $pageInfo['pageAt'] >= $pageInfo['pageTotal'] ? true : false;

    $pageInfo['pagePrevious'] = $pageInfo['pageAtFirst'] ? 1 : $pageInfo['pageAt'] - 1;
    $pageInfo['pageNext'] = $pageInfo['pageAtLast'] ? $pageInfo['pageEnd'] : $pageInfo['pageAt'] + 1;

    return $pageInfo;
  }

  /**
   * 返回 getPagerInfo 方法的一个子集
   *
   * @param int $rowsTotal
   * @param int $rowsPerPage
   * @param int $atPage
   * @param int $displayPageAreaNum
   * @return array
   */
  public static function getPagerArray($rowsTotal, $rowsPerPage = 10, $atPage = 1, $displayPageAreaNum = 5)
  {
    $pageInfo = self::getPagerInfo($rowsTotal, $rowsPerPage, $atPage, $displayPageAreaNum);

    return $pageInfo['pageArea'];
  }

  /**
   * 在没有url参数的时候，用来生成默认的Url样式
   *
   * @return string
   */
  public static function getDefaultUrlFormat()
  {
    $url = nbRequest::getInstance()->getUrl();

    if (!nbRequest::getInstance()->getGet('page'))
    {
      if (strpos($url, '?'))
      {
        $urlFormat = $url.'&page=%p%';
      }
      else
      {
        $urlFormat = $url.'?page=%p%';
      }
    }
    else
    {
      $urlFormat = preg_replace('/page=\d+/', 'page=%p%', $url);
    }

    return $urlFormat;
  }

  /**
   * 生成链接的方法，使用%p%作为token符号
   *
   * @param string $urlFormat
   * @param int $pageNumber
   * @return string
   */
  public static function getUrl($pageNumber, $urlFormat = null)
  {
    if (!$urlFormat)
    {
      $urlFormat = self::getDefaultUrlFormat();
    }
    return preg_replace('/%p%/', $pageNumber, $urlFormat);
  }
}