<?php
/**
 * Copyright (c) 2010 ST, Inc. All Rights Reserved.
 *
 * THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF CS, Inc.
 * The copyright notice above does not evidence any
 * actual or intended publication of such source code.
 */

/**
 * 载入一个app应用
 *
 */
function loadApp($appName, $type = 'addons')
{
  // temp add this code
  if (preg_match('/\//', $appName))
  {
    echo 'dont need / in appName: '.$appName;
  }
  $appName = str_replace('-', '/', $appName);

  if ($type == 'addons')
  {
    include(ADDONS_ROOT."$appName/load.php");
  }
  else
  {
    include(PROJECT_ROOT."apps/$appName/load.php");
  }
}

//function loadConfig($configPath, $mustHave = true)
//{
//  AppConfig::clear();
//
//  if ($mustHave && !is_file($configPath))
//  {
//    echo $configPath.' is not exist!';
//  }
//
//  if (is_file($configPath))
//  {
//    include ($configPath);
//    foreach (isset($config) ? $config : array() as $configName => $valuePeer)
//    {
//      foreach ($valuePeer as $key => $value)
//      {
//        AppConfig::set("$configName/$key", $value);
//      }
//    }
//  }
//}

//function getAppName()
//{
//  preg_match('/^\/?(\w+)\.php.*/', $_SERVER['REQUEST_URI'], $matchs);
//
//  $appName = isset($matchs[1]) ? $matchs[1] : CoreConfig::get('core/defaultApp');
//
//  return str_replace('-', '/', $appName);
//}

//function arrayBind($oldValue, $newValue, $length)
//{
//  if (count($oldValue) >= $length)
//  {
//    array_pop($oldValue);
//  }
//
//  return array_merge((array)$newValue, $oldValue);;
//}

function p($value = '')
{
  print_r($value);
  exit;
}

//function readFilesRecursive($path, $regex = '')
//{
//  $filenames = array();
//  foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename=>$cur)
//  {
//    if ($regex && preg_match($regex, $filename))
//    {
//      $filenames[] = $filename;
//    }
//  }
//
//  return $filenames;
//}



//function arrayRemove($array, $removeValue)
//{
//  foreach ($array as $key => $value)
//  {
//    if ($value == $removeValue)
//    {
//      unset($array[$key]);
//    }
//  }
//
//  return $array;
//}

function __($value)
{
  return $value;
}

//function arrayMergeReplaceRecursive()
//{
//  // Holds all the arrays passed
//  $params = & func_get_args ();
//
//  // First array is used as the base, everything else overwrites on it
//  $return = array_shift ( $params );
//
//  // Merge all arrays on the first array
//  foreach ( $params as $array )
//  {
//    foreach ( $array as $key => $value )
//    {
//      // Numeric keyed values are added (unless already there)
//      if (is_numeric ( $key ) && (! in_array ( $value, $return )))
//      {
//        if (is_array ( $value ))
//        {
//          $return [] = $this->arrayMergeReplaceRecursive ( $return [$$key], $value );
//        }
//        else
//        {
//          $return [] = $value;
//        }
//      }
//      else
//      {
//        if (isset ( $return [$key] ) && is_array ( $value ) && is_array ( $return [$key] ))
//        {
//          $return [$key] = arrayMergeReplaceRecursive ( $return [$key], $value );
//        }
//        else
//        {
//          $return [$key] = $value;
//        }
//      }
//    }
//  }
//
//  return $return;
//}

/**
 * Returns a formatted ID based on the <i>$name</i> parameter and optionally the <i>$value</i> parameter.
 *
 * This function determines the proper form field ID name based on the parameters. If a form field has an
 * array value as a name we need to convert them to proper and unique IDs like so:
 * <samp>
 *  name[] => name (if value == null)
 *  name[] => name_value (if value != null)
 *  name[bob] => name_bob
 *  name[item][total] => name_item_total
 * </samp>
 *
 * <b>Examples:</b>
 * <code>
 *  echo get_id_from_name('status[]', '1');
 * </code>
 *
 * @param  string $name   field name
 * @param  string $value  field value
 *
 * @return string <select> tag populated with all the languages in the world.
 */
//function get_id_from_name($name, $value = null)
//{
//  // check to see if we have an array variable for a field name
//  if (strstr($name, '['))
//  {
//    $name = str_replace(array('[]', '][', '[', ']'), array((($value != null) ? '_'.$value : ''), '_', '_', ''), $name);
//  }
//
//  return $name;
//}

//function executeTemplate($__templatePath, $vlaues)
//{
//  new nbLog("Execute template $__templatePath", 'mvc-template');
//  foreach ($vlaues as $__name => $__value)
//  {
//    $$__name = $__value;
//  }
//
//  if (file_exists($__templatePath))
//  {
//    ob_start();
//    include($__templatePath);
//    $contents = ob_get_contents();
//    ob_end_clean();
//  }
//  else
//  {
//    throw new nbAddonException('template not exists: '.$__templatePath);
//  }
//
//  return $contents;
//}

//function generateFile1($__templatePath, $targetPath, $vlaues)
//{
//  foreach ($vlaues as $__name => $__value)
//  {
//    $$__name = $__value;
//  }
//
//  ob_start();
//  include($__templatePath);
//  $content = ob_get_contents();
//  ob_end_clean();
//
//  writeFile($targetPath, $content, array('phpToken' => true));
//}
//


/**
 * Returns an array value for a path.
 *
 * @param array  $values   The values to search
 * @param string $name     The token name
 * @param array  $default  Default if not found
 *
 * @return array
 */
//function getArrayValueForPath($values, $name, $default = null)
//{
//  if (false === $offset = strpos($name, '['))
//  {
//    return isset($values[$name]) ? $values[$name] : $default;
//  }
//
//  if (!isset($values[substr($name, 0, $offset)]))
//  {
//    return $default;
//  }
//
//  $array = $values[substr($name, 0, $offset)];
//
//  while (false !== $pos = strpos($name, '[', $offset))
//  {
//    $end = strpos($name, ']', $pos);
//    if ($end == $pos + 1)
//    {
//      // reached a []
//      if (!is_array($array))
//      {
//        return $default;
//      }
//      break;
//    }
//    else if (!isset($array[substr($name, $pos + 1, $end - $pos - 1)]))
//    {
//      return $default;
//    }
//    else if (is_array($array))
//    {
//      $array = $array[substr($name, $pos + 1, $end - $pos - 1)];
//      $offset = $end;
//    }
//    else
//    {
//      return $default;
//    }
//  }
//
//  return $array;
//}

//function getCaller()
//{
//  $request = Request::getInstance();
//
//  return nbAppHelper::getAppName().'/'.$request->getModuleName().'/'.$request->getActionName();
//}

//function setConfig($name, $value)
//{
//  $pathInfo = PathTool::readPath('/');
//  $config = ConvertTool::toPascal($pathInfo['app']).'Config';
//
//  if (file_exists($pathInfo['appRoot'].'config/'.$config.'.class.php'))
//  {
//    $reflectionClass = new ReflectionClass($config);
//
//    return $reflectionClass->setStaticPropertyValue($name, $value);
//  }
//  else
//  {
//    return Config::$$name = $value;
//  }
//}