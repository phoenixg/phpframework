<?php
class NbTableComponents extends nbAction
{
  public function indexAction($parameter)
  {
    $allTableConfig = nbConfigHelper::getConfig('nb-table/item');
    if (!isset($allTableConfig[$parameter['name']]))
    {
      throw new nbAddonException('do not have a table called csRoleTable.');
    }

    $this->config = $allTableConfig[$parameter['name']];
    $this->parameter = $parameter;

    $this->prepareSelect();
    $this->prepareSqlResult();
    $this->prepareHeaderValue();
    $this->prepareBodyValue();
    $this->preparePagerInfo();
    if (isset($this->config['template']))
    {
      $this->setTemplate($this->config['template']);
    }
  }

  protected function prepareSelect()
  {
    $page = nbRequest::getInstance()->getGet('page', 1);
    if (isset($this->config['rowsPerPage']))
    {
      $this->rowsPerPage = $this->config['rowsPerPage'];
    }
    else
    {
      $this->rowsPerPage = nbConfigHelper::getConfig('nb-table/rowsPerPage');
    }

    $select = $this->parameter['builder'];

    $select->modifyLimit($this->rowsPerPage*($page - 1), $this->rowsPerPage);

    $sortValue = nbRequest::getInstance()->getGet('sort');
    if ($sortValue)
    {
      list($sortColumn, $sortWay) = explode('|', $sortValue);
      $select->setOrderBy(array('field' => $sortColumn, 'type' => strtolower($sortWay)));
    }

    $this->select = $select;
  }

  protected function prepareSqlResult()
  {
    list($this->result, $this->total) = nbQuery::selectWithTotal($this->select->toSelect());
  }

  protected function prepareHeaderValue()
  {
    if (!isset($this->config['columns']))
    {
      foreach ($this->result[0] as $rowKey => $resultRow)
      {
        $this->config['columns'][$rowKey] = array('title' => ConvertTool::toDisplay($rowKey));
      }
    }

    foreach ($this->config['columns'] as $columnName => $columnPara)
    {
      if (isset($columnPara['title']))
      {
        $title = $columnPara['title'];
      }
      else
      {
        $title = ConvertTool::toDisplay($columnName);
      }

      if (isset($this->config['columns'][$columnName]['sort']) && $this->config['columns'][$columnName]['sort'])
      {
        if ($type = $this->select->getSortTypeByFieldName($columnName))
        {
          $title .= $type == 'desc' ? '▼' : '▲';
          $sortWay = $type == 'asc' ? 'DESC' : 'ASC';
        }
        else
        {
          $sortWay = 'ASC';
        }

        if (nbRequest::getInstance()->getGet('sort'))
        {
          $url = preg_replace('/sort=\w+\|\w+&?/', 'sort='.$columnName.'|'.$sortWay.'&', nbRequest::getInstance()->getUrl());
          $title = nbWidget::linkTo($title, $url);
        }
        else
        {
          $url = nbRequest::getInstance()->getUrl();
          $separater = strstr($url, '?') ? '&' : '?';

          $title = nbWidget::linkTo($title, $url.$separater.'sort='.$columnName.'|'.$sortWay);
        }
      }

      $attributeString = '';
      if (isset($columnPara['thAttribute']))
      {
        foreach ($columnPara['thAttribute'] as $attributeName => $attributeValue)
        {
          $attributeString .= ' '.$attributeName.'="'.$attributeValue.'"';
        }
      }

      $this->tableTitle[$columnName] = "<th$attributeString>".$title."</th>";
    }
  }

  protected function prepareBodyValue()
  {
    $this->tableBody = array();
    foreach ($this->config['columns'] as $columnName => $columnPara)
    {
      foreach ($this->result as $rowKey => $resultRow)
      {
        $this->tableBody[$rowKey][$columnName] = $this->formatCell($columnName, $columnPara, $resultRow);
      }
    }
  }

  protected function preparePagerInfo()
  {
    $page = nbRequest::getInstance()->getGet('page', 1);
    $this->pagerInfo = nbPagerHelper::getPagerInfo($this->total, $page, $this->rowsPerPage);
  }

  private function formatCell($cellName, $columnPara, $resultRow)
  {
    if (isset($this->config['columns'][$cellName]) && isset($this->config['columns'][$cellName]['function']))
    {
      $functions = $this->config['columns'][$cellName]['function'];
      foreach ($functions as $functionName => $parameters)
      {
        foreach ($parameters as $key => $parameter)
        {
          $parameters[$key] = preg_replace('/%([\w:]+)%/e', "\$resultRow['\\1']", $parameter);
        }

        $row[$functionName] = call_user_func_array($functionName, $parameters);
        $displayValue = $row[$functionName];
      }
    }
    else
    {
      $displayValue = $resultRow[$cellName];
    }

    $attributeString = '';
    if (isset($columnPara['tdAttribute']))
    {
      foreach ($columnPara['tdAttribute'] as $attributeName => $attributeValue)
      {
        $attributeString .= ' '.$attributeName.'="'.$attributeValue.'"';
      }
    }

    return "<td$attributeString>".$displayValue."</td>";
  }
}