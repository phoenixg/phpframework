<?php
class HeadHelper
{
  /**
   * title
   */
  private static $title = '';
  private static $css = array();
  private static $js = array();
  private static $meta = array(
    'keyword' => '',
    'title' => '',
    'content-type' => 'utf-8'
  );

  public static function getTitle()
  {
    return self::$title;
  }

  public static function setTitle($value)
  {
    self::$title = $value;
  }

  public static function addTitle($value)
  {
    self::$title += $value;
  }

  public static function getCss()
  {
    return self::$css;
  }

  /**
   *
   * @param mix $value
   * @param array $options
   * @return unknown_type
   */
  public static function setCss($value, $options = array())
  {
    self::$css[$value] = $options;
  }

  /**
   *
   * @param mix $value
   * @param array $options
   * @return unknown_type
   */
  public static function unsetCss($value)
  {
    unset(self::$css[$value]);
  }

  public static function getJs()
  {
    return self::$js;
  }

  public static function setJs($value, $options = array())
  {
    self::$js[$value] = $options;
  }

  public static function unsetJs($value)
  {
    unset(self::$js[$value]);
  }

  public static function getMeta($name)
  {
    return self::$meta[$name];
  }

  public static function setMeta($name, $value)
  {
    self::$meta[$name] = $value;
  }

  public static function unsetMeta($name)
  {
    unset(self::$meta[$name]);
  }

  public static function getMetas()
  {
    return self::$meta;
  }

  public static function renderTitle()
  {
    return '<title>'.self::$title.'</title>'."\n";
  }

  public static function renderCss_old()
  {
    $cssString = '';

    foreach (self::$css as $path => $options)
    {
      if (isset($options['position']) && $options['position'] == 'first')
      {
        $cssString .= '<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.(isset($options['media']) ? $options['media'] : 'all').'">'."\n";
        unset(self::$css[$path]);
      }
    }

    foreach (self::$css as $path => $options)
    {
      $cssString .= '<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.(isset($options['media']) ? $options['media'] : 'all').'">'."\n";
    }

    return $cssString;
  }

  public static function renderCss()
  {
    $string = '<script>
var loadCss = function(url)
{
  var node = document.createElement("link");
  node.type = "text/css";
  node.rel = "stylesheet";
  node.media = "all";
  node.href = url;
  top.document.getElementsByTagName("head")[0].appendChild(node);
}'."\n";
    foreach (self::$css as $path => $options)
    {
      if (isset($options['position']) && $options['position'] == 'first')
      {
        $string .= 'loadCss("'.$path.'");'."\n";
        unset(self::$css[$path]);
      }
    }

    foreach (self::$css as $path => $options)
    {
      $string .= 'loadCss("'.$path.'");'."\n";
    }

    $string .= '</script>'."\n";

    return $string;
  }

  public static function renderJs_old()
  {
    $jsString = '';

    foreach (self::$js as $path => $options)
    {
      if (isset($options['position']) && $options['position'] == 'first')
      {
        $jsString .= '<script type="text/javascript" src="'.$path.'"></script>'."\n";
        unset(self::$js[$path]);
      }
    }

    foreach (self::$js as $path => $options)
    {
      $jsString .= '<script type="text/javascript" src="'.$path.'"></script>'."\n";
    }

    return $jsString;
  }

  public static function renderJs()
  {
    $string = '<script>
var loadScriptHistory = loadScriptHistory || [];

var loadScript = function(url)
{
  for(i=0;i<loadScriptHistory.length;i++)
  {
      if(loadScriptHistory[i] == url)
      {
        return;
      }
  }

  loadScriptHistory[loadScriptHistory.length] = url;

  var node = document.createElement("script");
  node.type = "text/javascript";
  node.src = url;
  top.document.getElementsByTagName("head")[0].appendChild(node);
}'."\n";
    foreach (self::$js as $path => $options)
    {
      if (isset($options['position']) && $options['position'] == 'first')
      {
        $string .= 'loadScript("'.$path.'");'."\n";
        unset(self::$js[$path]);
      }
    }

    foreach (self::$js as $path => $options)
    {
      $string .= 'loadScript("'.$path.'");'."\n";
    }

    $string .= '</script>'."\n";

    return $string;
  }

  public static function renderMeta()
  {
    $cssString = '';

    foreach (self::$css as $path => $options)
    {
      if (isset($options['position']) && $options['position'] == 'first')
      {
        $cssString .= '<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.isset($options['media']) ? $options['media'] : 'all'.'">'."\n";
        unset(self::$css[$path]);
      }
    }

    foreach (self::$css as $path => $options)
    {
      $cssString .= '<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.isset($options['media']) ? $options['media'] : 'all'.'">'."\n";
    }

    return $cssString;
  }
}