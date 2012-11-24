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
class nbResponse
{
  private static $instance;

  /**
   *
   * Enter description here...
   * @return nbResponse
   */
  public static function getInstance()
  {
    if (self::$instance == null)
    {
      $class = __CLASS__;
      self::$instance = new self();
    }

    return self::$instance;
  }

  public $headers = array();
  public $title = '';
  public $js = array();
  public $css = array();
  public $meta = array();

  static protected $statusTexts = array(
    '100' => 'Continue',
    '101' => 'Switching Protocols',
    '200' => 'OK',
    '201' => 'Created',
    '202' => 'Accepted',
    '203' => 'Non-Authoritative Information',
    '204' => 'No Content',
    '205' => 'Reset Content',
    '206' => 'Partial Content',
    '300' => 'Multiple Choices',
    '301' => 'Moved Permanently',
    '302' => 'Found',
    '303' => 'See Other',
    '304' => 'Not Modified',
    '305' => 'Use Proxy',
    '306' => '(Unused)',
    '307' => 'Temporary Redirect',
    '400' => 'Bad Request',
    '401' => 'Unauthorized',
    '402' => 'Payment Required',
    '403' => 'Forbidden',
    '404' => 'Not Found',
    '405' => 'Method Not Allowed',
    '406' => 'Not Acceptable',
    '407' => 'Proxy Authentication Required',
    '408' => 'Request Timeout',
    '409' => 'Conflict',
    '410' => 'Gone',
    '411' => 'Length Required',
    '412' => 'Precondition Failed',
    '413' => 'Request Entity Too Large',
    '414' => 'Request-URI Too Long',
    '415' => 'Unsupported Media Type',
    '416' => 'Requested Range Not Satisfiable',
    '417' => 'Expectation Failed',
    '500' => 'Internal Server Error',
    '501' => 'Not Implemented',
    '502' => 'Bad Gateway',
    '503' => 'Service Unavailable',
    '504' => 'Gateway Timeout',
    '505' => 'HTTP Version Not Supported',
  );

  /**
   *
   * @param integer $code
   * @param string $name
   * @return unknown_type
   */
  public function setStatusCode($code, $name = null)
  {
    $this->statusCode = $code;
    $this->statusText = null !== $name ? $name : self::$statusTexts[$code];
  }

  /**
   *
   * @param string $name
   * @param string $value
   * @param boll $replace
   * @return void
   */
  public function setHttpHeader($name, $value, $replace = true)
  {
    //TODO
  }

  /**
   *
   * @param string $name
   * @param string $default
   * @return void
   */
  public function getHttpHeader($name, $default = null)
  {
    //TODO
  }

  /**
   *
   * @param string $value
   * @return void
   */
  public function setContentType($value)
  {
    //TODO
  }

  /**
   *
   * @return unknown_type
   */
  public function sendHttpHeaders()
  {

  }

  /**
   *
   * @param string $address
   * @param string $queryString
   * @return void
   */
  public function redirect($address, $queryString = '')
  {

  }

  /**
   *
   * @return unknown_type
   */
  public function renderHead()
  {
    $str = '';
    $str .= '<meta http-equiv="content-type" content="text/html;charset=UTF-8" />'."\n";
    $str .= '<title>'.nbData::get('title', 'html-head-value', '').'</title>'."\n";

    if (nbData::get('base', 'html-head-value'))
    {
      $str .= '<base href="'.nbData::get('base', 'html-head-value').'" />'."\n";
    }

    foreach (nbData::get('js', 'html-head-value') as $js)
    {
      $str .= '<script type="text/javascript" src="'.$js.'"></script>'."\n";
    }

    foreach (nbData::get('css', 'html-head-value') as $css)
    {
      $str .= '<link rel="stylesheet" href="'.$css.'" type="text/css" />';
    }

    foreach (nbData::get('meta', 'html-head-value') as $name => $meta)
    {
      if ($name != 'content-type')
      {
        $str .= '<meta name="'.$name.'" content="'.$meta.'" />'."\n";
      }
    }

    return $str;
  }

  public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false)
  {
    if ($expire !== null)
    {
      if (is_numeric($expire))
      {
        $expire = (int) $expire;
      }
      else
      {
        $expire = strtotime($expire);
        if ($expire === false || $expire == -1)
        {
          throw new nbCoreException('Your expire parameter is not valid.');
        }
      }
    }

    setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
  }

  public function delCookie($name)
  {
    if (isset($_COOKIE[$name]))
    {
      unset($_COOKIE[$name]);
    }
  }
}