<?php
class nbQueryBuilder
{
  public $tables;
  public $relation = array();

  public function toSelect()
  {
    $qe = new nbQueryExecuter();
    $qe->init(array('table' => $this->tables, 'relation' => $this->relation));

    return $qe->toSelect();
  }

  public function toUpdate()
  {
    $qe = new nbQueryExecuter();
    $qe->init(array('table' => $this->tables, 'relation' => $this->relation));

    return $qe->toUpdate();
  }

  public function toInsert()
  {
    $qe = new nbQueryExecuter();
    $qe->init(array('table' => $this->tables, 'relation' => $this->relation));

    return $qe->toInsert();
  }

  public function toDelete()
  {
    $qe = new nbQueryExecuter();
    $qe->init(array('table' => $this->tables, 'relation' => $this->relation));

    return $qe->toDelete();
  }

  public function addTable($table)
  {
    if (is_array($table) && isset($table['tables']))
    {
      foreach ($table['tables'] as $key => $oneTable)
      {
        if ($this->tableNotExist($oneTable))
        {
          $this->tables[] = $this->format($oneTable);
        }
      }

      $this->relation = $table['relation'];
    }
    else
    {
      if ($this->tableNotExist($table))
      {
        $this->tables[] = $this->format($table);
      }
    }
  }

  private function tableNotExist($table)
  {
    if (!$this->tables)
    {
      return true;
    }

    foreach ($this->tables as $oneTable)
    {
      if (isset($table['alias']) && isset($oneTable['alias']) && $table['alias'] == $oneTable['alias'])
      {
        return false;
      }

      if ((!isset($table['alias']) || !isset($oneTable['alias'])) && $table['name'] == $oneTable['name'])
      {
        return false;
      }
    }

    return true;
  }

  public function ____construct($mainTable, $option = array())
  {
    if (isset($mainTable['tables']))
    {
      foreach ($mainTable['tables'] as $key => $table)
      {
        if ($key === 0)
        {
          $this->tables[0] = $this->format($table);
        }
        else
        {
          $relationMethod = $mainTable['tableRelation'][$key - 1]['type'];
          $condition = $mainTable['tableRelation'][$key - 1]['condition'];

          $this->$relationMethod($table, $condition);
        }
      }
    }
    else
    {
      $this->tables[0] = $this->format($mainTable);
    }
  }

  public function addSelectFieldString($selectFieldString)
  {
    $this->tables[0]['field'] = $selectFieldString;
  }

  public function modifyLimit($start, $offset)
  {
    $this->tables[0]['limit'] = array($start, $offset);
  }

  public function addRelation($tableLeft, $tableRight, $condition, $type = 'leftJoin')
  {
    $this->addTable($tableLeft);
    $this->addTable($tableRight);
    $condition = $this->formatCondition($condition);
    foreach ($condition as $key => $subCondition)
    {
      $condition[$key]['leftTable'] = $this->getTableByName($tableLeft);
      $condition[$key]['rightTable'] = $this->getTableByName($tableRight);
    }

    $this->relation[] = array(
      'type' => $type,
      'condition' => $condition,
    );
  }

  private function getTableByName($tableName)
  {
    if (!is_string($tableName))
    {
      return $this->formatTable($tableName);
    }
    else
    {
      foreach ($this->tables as $table)
      {
        if ($table['alias'] == $tableName || $table['name'] == $tableName)
        {
          return $table;
        }
      }
    }

    throw new nbAddonException("do not have a table called $tableName");
  }

  private function format($table)
  {
    $table = $this->formatTable($table);
    if (isset($table['field']))
    {
      $table['field'] = $this->formatSelectField($table['field']);
    }

    if (isset($table['orderBy']))
    {
      $table['orderBy'] = $this->formatOrderBy($table['orderBy']);
    }

    if (isset($table['groupBy']))
    {
      $table['groupBy'] = $this->formatGroupBy($table['groupBy']);
    }

    return $table;
  }

  private function formatTable($table)
  {
    if (is_string($table))
    {
      $table = array('name' => $table);
    }

    return $table;
  }

  private function formatSelectField($filed)
  {
    if ($filed == '*')
    {
      $filed = array('name' => '*');
    }

    if (1 == nbArrayHelper::getArrayDeepLevel($filed))
    {
      $filed = array($filed);
    }

    return $filed;
  }

  private function formatCondition($condition)
  {
    if (1 == nbArrayHelper::getArrayDeepLevel($condition))
    {
      $condition = array($condition);
    }

    return $condition;
  }

  private function formatOrderBy($orderBy)
  {
    if (1 == nbArrayHelper::getArrayDeepLevel($orderBy))
    {
      $orderBy = array($orderBy);
    }

    return $orderBy;
  }

  private function formatGroupBy($groupBy)
  {
    if (1 == nbArrayHelper::getArrayDeepLevel($groupBy))
    {
      $groupBy = array($groupBy);
    }

    return $groupBy;
  }


  public function getSortTypeByFieldName($fieldName)
  {
    foreach ($this->tables as $table)
    {
      if (isset($table['orderBy']))
      {
        foreach ($table['orderBy'] as $orderBy)
        {
          if ($orderBy['field'] == $fieldName)
          {
            return $orderBy['type'];
          }
        }
      }
    }

    return false;
  }

  /**
   * this method would remove other order by and set this only
   *
   * @param array $orderBy
   */
  public function setOrderBy($orderBy)
  {

    foreach ($this->tables as $tableKey => $table)
    {
      if (isset($this->tables[$tableKey]['orderBy']))
      {
        unset($this->tables[$tableKey]['orderBy']);
      }
    }

    $orderBy = $this->formatOrderBy($orderBy);

    foreach ($orderBy as $eachOrder)
    {
      $this->addOrderBy($eachOrder['field'], $eachOrder['type']);
    }
  }

  /**
   * sort value must exist in the table field and need to know belong to which table
   * @param $field
   * @param $type
   */
  public function addOrderBy($field, $type)
  {
    foreach ($this->tables as $tableKey => $table)
    {
      if (isset($table['field']) && is_array($table['field']))
      {
        foreach ($table['field'] as $fields)
        {
          if ($fields['name'] == $field || $fields['name'] == '*')
          {
            if (isset($fields['alias']))
            {
              $field = $fields['alias'];
            }
            $this->tables[$tableKey]['orderBy'][] = array('field' => $field, 'type' => $type);
          }
        }
      }
      else
      {
        $this->tables[$tableKey]['orderBy'][] = array('field' => $field, 'type' => $type);
      }
    }
  }

  public function setUpdateValue($updateValue)
  {
    $this->tables[0]['value'] = $updateValue;
  }

  public function setInsertValue($insertValue)
  {
    $this->tables[0]['value'] = $insertValue;
  }

  public function setCondition($condition)
  {
    $this->tables[0]['condition'] = $condition;
  }
}