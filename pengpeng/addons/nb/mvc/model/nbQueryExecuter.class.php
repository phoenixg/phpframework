<?php
class nbQueryExecuter
{
  public function init($query)
  {
    $this->tables = $query['table'];
    $this->relation = $query['relation'];
  }

  public function toSelect()
  {
    $selectString = $this->executeSelectField();
    $fromString = $this->executeTable();
    $whereString = $this->executeCondition();
    $orderString = $this->executeOrderBy();
    $groupString = $this->executeGroupBy();
    $limitString = $this->executeLimit();

    $sql = $selectString . $fromString . $whereString . $groupString . $orderString . $limitString;
    return $sql;
  }

  public function toUpdate()
  {
    $fromString = $this->executeTable();
    $updateString = $this->executeUpdateValue();
    $whereString = $this->executeCondition();

    $sql = 'UPDATE ' . $this->tables[0]['name'] . $updateString . $whereString;
    return $sql;
  }

  public function toInsert()
  {
    $insertString = $this->executeInsertValue();

    $sql = 'INSERT INTO ' . $this->tables[0]['name'] . $insertString;

    return $sql;
  }

  public function toDelete()
  {
    $whereString = $this->executeCondition();

    $sql = 'DELETE FROM ' . $this->tables[0]['name'] . $whereString;

    return $sql;
  }

  private function executeInsertValue()
  {
    $fieldKey = $fieldValue = array();

    if (is_array($this->tables[0]['value']))
    {
      foreach ($this->tables[0]['value'] as $name => $value)
      {
        $fieldKey[] = $name;
        $fieldValue[] = mysql_escape_string($value);
      }
    }

    return ' (`'.implode('`, `', $fieldKey)."`) VALUES ('".implode("', '", $fieldValue)."')";
  }

  private function executeUpdateValue()
  {
    $fieldArray = array();

    if (is_array($this->tables[0]['value']))
    {
      foreach ($this->tables[0]['value'] as $name => $value)
      {
        $fieldArray[] = $name." = '$value'";
      }
    }
    else
    {
      $fieldArray[] = $this->tables[0]['value'];
    }

    return ' SET '.implode(', ', $fieldArray);
  }

  private function executeSelectField()
  {
    $fieldArray = array();

    foreach ($this->tables as $table)
    {
      if (is_array($table['field']))
      {
        foreach ($table['field'] as $field)
        {
          $fieldArray[] = $this->getAFieldStringForSelect($table, $field);
        }
      }
      else
      {
        $fieldArray[] = $table['field'];
      }
    }

    return 'SELECT '.implode(', ', $fieldArray);
  }

  private function executeTable()
  {
    $tableString = '';

    if ($this->relation)
    {
      $tableString .= $this->getATableAliasString($this->relation[0]['condition'][0]['leftTable']);

      foreach ($this->relation as $relationKey => $relation)
      {
        if (isset($relation['type']))
        {
          if ($relation['type'] == 'leftJoin')
          {
            $tableString .= ' LEFT JOIN '.$this->getATableAliasString($relation['condition'][0]['rightTable']). ' ON '. $this->getCondition($relation['condition']);
          }
        }
        else
        {

        }
      }
    }
    else
    {
      $tableString .= $this->getATableAliasString($this->tables[0]);
    }

    return ' FROM '.$tableString;
  }

//  private function getTableByName($name)
//  {
//    foreach ($this->tables as $table)
//    {
//      if ($table['name'] == $name)
//      {
//        return $table;
//      }
//    }
//  }

  private function executeCondition()
  {
    if (isset($this->tables[0]['condition']) && $this->tables[0]['condition'])
    {
      return ' WHERE '.$this->getCondition($this->tables[0]['condition']);
    }
    else
    {
      return '';
    }
  }

  private function executeLimit()
  {
    if (isset($this->tables[0]['limit']))
    {
      return " LIMIT {$this->tables[0]['limit'][0]}, {$this->tables[0]['limit'][1]}";
    }
  }

  private function executeOrderBy()
  {
    $orderByArray = array();

    foreach ($this->tables as $table)
    {
      if (!isset($table['orderBy']))
      {
        continue;
      }

      if (is_array($table['orderBy']))
      {
        foreach ($table['orderBy'] as $orderBy)
        {
          $orderByArray[] = $this->getAOrderByString($table, $orderBy);
        }
      }
      else
      {
        $orderByArray[] = $table['orderBy'];
      }
    }

    if ($orderByArray)
    {
      return " ORDER BY ".implode(', ', $orderByArray);
    }
  }

  private function executeGroupBy()
  {
    $groupByArray = array();

    foreach ($this->tables as $table)
    {
      if (!isset($table['groupBy']))
      {
        continue;
      }

      if (is_array($table['groupBy']))
      {
        foreach ($table['groupBy'] as $groupBy)
        {
          $groupByArray[] = $this->getAGroupByString($table, $groupBy);
        }
      }
      else
      {
        $groupByArray[] = $table['groupBy'];
      }
    }

    if ($groupByArray)
    {
      return " GROUP BY ".implode(', ', $groupByArray);
    }
  }

  private function getCondition($conditions)
  {
    $level = nbArrayHelper::getArrayDeepLevel($conditions);

    if ($level === 0)
    {
      return $conditions;
    }

    if ($level === 1)
    {
      $conditions = array($conditions);
    }

    $conditionString = '';
    foreach ($conditions as $key => $condition)
    {
      $connecter = (isset($condition['connecter']) && $condition['connecter'] == 'or') ? 'OR' : 'AND';
      $operater = (isset($condition['operater']) && $condition['operater']) ? $condition['operater'] : '=';

      if (isset($condition['leftField']))
      {
        if (isset($condition['leftTable']))
        {
          $left = $this->getAFieldAliasString($condition['leftTable'], $condition['leftField']);
        }
        else
        {
          $left = $condition['leftField'];
        }
      }
      else if (isset($condition['leftValue']))
      {
        $quoteType = isset($condition['leftValueQuote']) ? $condition['leftValueQuote'] : "'";

        $left = $this->getAConditionValueString($condition['leftValue'], $quoteType);
      }

      if (isset($condition['rightValue']))
      {
        $quoteType = isset($condition['rightValueQuote']) ? $condition['rightValueQuote'] : "'";

        $right = $this->getAConditionValueString($condition['rightValue'], $quoteType);
      }
      else if (isset($condition['rightField']))
      {
        $right = $this->getAFieldAliasString($condition['rightTable'], $condition['rightField']);
      }

      if (isset($condition['quohao']) && is_numeric($condition['quohao']))
      {
        $conditionString .= '(';
        $this->closeKey = $key + $condition['quohao'];
      }

      if ($key == 0)
      {
        $conditionString .= $left.' '.$operater.' '.$right;
      }
      else
      {
        $conditionString .= " $connecter ".$left.' '.$operater.' '.$right;
      }

      if (isset($this->closeKey) && $key == $this->closeKey)
      {
        $conditionString .= ')';
        unset($this->closeKey);
      }
    }

    return $conditionString;
  }

  private function getAFieldStringForSelect($table, $field)
  {
    $tableAlias = isset($table['alias']) ? $table['alias'] : '';

    $fieldString = '';
    if (isset($field['function']))
    {
      $fieldString .= strtoupper($field['function']).'(';
    }

    if (isset($field['distinct']) && $field['distinct'])
    {
      $fieldString .= 'DISTINCT ';
    }

    if ($tableAlias && $field['name'] != '*')
    {
      $fieldString .= $tableAlias.'.';
    }

    $fieldString .= $field['name'];

    if (isset($field['function']))
    {
      $fieldString .= ')';
    }

    if (isset($field['alias']))
    {
      $fieldString .= ' '.$field['alias'];
    }

    return $fieldString;
  }

  /**
   * get a string like 'user u'
   *
   * @param array $table
   * @return string
   */
  private function getATableAliasString($table)
  {
    $alias = $this->getATableAlias($table);
    if ($alias)
    {
      return $table['name'].' '.$alias;
    }
    else
    {
      return $table['name'];
    }
  }

  private function getATableAlias($table)
  {
    return isset($table['alias']) ? $table['alias'] : '';
  }

  private function getAConditionValueString($value, $quoteType)
  {
    if ($quoteType === false || $quoteType == '')
    {
      return $value;
    }
    else if ($quoteType == "'")
    {
      return "'".$value."'";
    }
    else if ($quoteType == '"')
    {
      return '"'.$value.'"';
    }
  }

  private function getAOrderByString($table, $orderBy)
  {
    if (!isset($orderBy['type']) || $orderBy['type'] == 'asc')
    {
      $orderByType = 'ASC';
    }
    else
    {
      $orderByType = 'DESC';
    }

    $orderByField = '';
    if (is_array($table['field']))
    {
      foreach ($table['field'] as $field)
      {
        if (isset($field['alias']) && $field['name'] == $orderBy['field'])
        {
          $orderByField = $field['alias'];
        }
      }
    }

    if (!$orderByField)
    {
      $tableAlias = $this->getATableAlias($table);
      if ($tableAlias)
      {
        $orderByField = $tableAlias.'.'.$orderBy['field'];
      }
      else
      {
        $orderByField = $orderBy['field'];
      }
    }

    return $orderByField.' '.$orderByType;
  }

  private function getAGroupByString($table, $groupBy)
  {
    $groupByField = '';
    foreach ($table['field'] as $field)
    {
      if (isset($field['alias']) && $field['name'] == $groupBy['field'])
      {
        $groupByField = $field['alias'];
      }
    }

    if (!$groupByField)
    {
      $tableAlias = $this->getATableAlias($table);
      if ($tableAlias)
      {
        $groupByField = $tableAlias.'.'.$groupBy['field'];
      }
      else
      {
        $groupByField = $groupBy['field'];
      }
    }

    return $groupByField;
  }

  private function getAFieldAliasString($table, $fieldName)
  {
    if (isset($table['alias']))
    {
      return $table['alias'].'.'.$fieldName;
    }
    else
    {
      return $fieldName;
    }
  }
}