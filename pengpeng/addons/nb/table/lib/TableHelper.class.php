<?php
class TableHelper
{
  /**
   * used to display the content in <th> and </th>
   *
   * @param table $table the instance of table class
   * @param string $columnKey the column which need to display
   *
   * @return string
   */
  public static function displayHeader($table, $columnKey)
  {
    $style = '';

    if (!isset($table->column[$columnKey]['header']))
    {
      // better to change a_word_of_header to A Word To Header
      $title = __(ConvertTool::toDisplay($columnKey));
    }
    else
    {
      $title = __($table->column[$columnKey]['header']);
    }

    if ($table->displayTitleSort($columnKey))
    {
      if ('NOTSORTING' == $table->getCurrentSortWay($columnKey))
      {
        $title = self::linkTo($table, $title, "[sort][$columnKey]=ASC");
      }
      else if ('ASC' == $table->getCurrentSortWay($columnKey))
      {
        $title = self::linkTo($table, $title . '▲', "[sort][$columnKey]=DESC");
      }
      else
      {
        $title = self::linkTo($table, $title . '▼', "[sort][$columnKey]=ASC");
      }
    }

    return "<th style=\"$style\">" . $title . '</th>';
  }

  public static function displayCell($table, $columnKey, $row)
  {
    $style = '';

    $functionNames = $table->column[$columnKey]['function'];

    if (is_array($functionNames))
    {
      foreach ($functionNames as $functionName => $parameters)
      {
        foreach ($parameters as $key => $parameter)
        {
          $parameters[$key] = preg_replace('/%([\w:]+)%/e', "\$row['\\1']", $parameter);
        }

        if (!method_exists($table, $functionName))
        {
          $row[$functionName] = call_user_func_array($functionName, $parameters);
          $displayValue = $row[$functionName];
        }
        else
        {
          $row[$functionName] = call_user_func_array(array($table, $functionName), $parameters);
          $displayValue = $row[$functionName];
        }
      }
    }
    else
    {
      throw new StopException("method $functionNames is not exist");
    }

    if (isset($table->column[$columnKey]['style']))
    {
      $style .= $table->column[$columnKey]['style'];
    }

    if ($table->isFirstColumn($columnKey))
    {
      $class = ' class = "first"';
    }
    else if ($table->isLastColumn($columnKey))
    {
      $class = ' class = "last"';
    }
    else
    {
      $class = '';
    }

    return "<td$class style=\"$style\">" . $displayValue . '</td>';
  }

  public static function displaySearch($table, $searchKey)
  {
    $searchLabel = isset($table->search[$searchKey]['name']) ? $table->search[$searchKey]['name'].': ' : $searchKey.': ';

    $searchValue = Request::getInstance()->getGet("{$table->getTableId()}[search][$searchKey]") ? Request::getInstance()->getGet("{$table->getTableId()}[search][$searchKey]") : '';

    if (isset($table->search[$searchKey]['select']))
    {
      return $searchLabel.select_tag($searchKey, options_for_select($table->search[$searchKey]['select'], $searchValue, array('include_blank' => true)), array('name' => "{$table->getTableId()}[search][$searchKey]"));
    }
    else
    {
      return $searchLabel.input_tag($searchKey, $searchValue, array('name' => "{$table->getTableId()}[search][$searchKey]"));
    }
  }

  /**
   * Use for do link for table display
   *
   * @param string $title
   * @param array $params
   * @param array $attributes
   * @return string
   */
  public function linkTo($table, $title, $params, $attributes = array())
  {
    // give the base table id
    //$temp = array('table' => $this->getTableId());
    $urlType = isset($attributes['urlType']) ? $attributes['urlType'] : 'replace';
    unset($attributes['urlType']);

    parse_str($table->getTableId().$params, $needQueryArray);

    if ($table->bindParameter)
    {
      foreach ($table->bindParameter as $key)
      {
        $temp[$table->getTableId()][$key] = $table->getTableParameter($key);
      }
    }

    if (isset($needQueryArray[$table->getTableId()]['sort']))
    {
      unset($temp[$table->getTableId()]['sort']);
    }

    $temp = arrayMergeReplaceRecursive($temp, $needQueryArray);

    $query = http_build_query($temp);

    //////////////////////////////////////////////////

    // keep the filter parameter in query
    $filter = $table->getFilter();

    foreach ($filter as $key => $value)
    {
      if (is_array($value))
      {
        foreach ($value as $subKey => $subValue)
        {
          $temp["{$table->getTableId()}[filter][$key][$subKey]"] = $subValue;
        }
      }
      else
      {
        $temp["{$table->getTableId()}[filter][$key]"] = $value;
      }
    }

    // keep the sort parameter in query
    foreach ($table->getTableParameter('sort') as $key => $value)
    {
      $temp["{$table->getTableId()}[sort][$key]"] = $value;
    }

    // keep the page parameter in query
    if ($table->getTableParameter('page'))
    {
      $temp["{$table->getTableId()}[page]"] = $table->getTableParameter('page');
    }

    // if a table is displayed by ajax, serviceAction would be keep in URL to remind which module and action it should at
    if (Request::getInstance()->getGet("serviceAction"))
    {
      $temp["serviceAction"] = Request::getInstance()->getGet("serviceAction");
    }

    foreach ($temp as $key => $value)
    {
      // remove the query have no value
      if (empty($value))
      {
        unset($temp[$key]);
      }
    }

    // remove the query with empty value
    foreach ($temp as $key => $value)
    {
      if (empty($value))
      {
        unset($temp[$key]);
      }
    }

    // remove the query for duplicate name for sort
    foreach ($temp as $key => $value)
    {
      if (preg_match('/\[sort\]/', $key))
      {
        $sortKey = $key;
        $sortValue = $value;
        unset($temp[$key]);
      }
    }
    if (isset($sortKey))
    {
      $temp[$sortKey] = $sortValue;
    }

    // convert the query to query_array
    $queryArray = array();
    foreach ($temp as $key => $value)
    {
      if (is_array($value))
      {
        foreach ($value as $subKey => $subValue)
        {
          $queryArray[] = $key . "[$subKey]=" . $subValue;
        }
      }
      else
      {
        $queryArray[] = $key . '=' . $value;
      }
    }

    $ajaxUrl = HtmlHelper::url('table/show/table', 'table='.ConvertTool::toPascal($table->getTableId()).'&'.$query.'&caller=admin/news/list');

    $attributes += array('query' => 'table='.ConvertTool::toPascal($table->getTableId()).'&'.$query, 'onclick' => "tablePage('$ajaxUrl');return false");

    return HtmlHelper::linkTo($title,
      Request::getInstance()->getGet('caller') ? Request::getInstance()->getGet('caller') : Request::getInstance()->getActionName(),
      $attributes);
  }
}