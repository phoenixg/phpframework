<?php
class NbFormComponents extends nbAction
{
  public function indexAction($parameter)
  {
    $allConfig = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    $this->config = $allConfig[$parameter['name']];
    $this->formType = $parameter['type'];
    if ($this->formType == 'edit')
    {
      $this->editValue = $parameter['editValue'];
    }
    else
    {
      $this->editValue = false;
    }

    $this->prepareFormAttribute();
    $this->prepareFieldBox();
    $this->prepareFieldTitle();
    $this->prepareFieldContent();
  }

  public function prepareFormAttribute()
  {
    $this->attributeString = '';
    if (isset($this->config['formAttribute']))
    {
      foreach ($this->config['formAttribute'] as $attributeName => $attributeValue)
      {
        if ($attributeName == 'action')
        {
          if (is_array($attributeValue))
          {
            $attributeValue = nbMvcWidget::url($attributeValue['path'], isset($attributeValue['query']) ? $attributeValue['query'] : '');
          }
        }
        $this->attributeString .= ' '.$attributeName.'="'.$attributeValue.'"';
      }
    }
  }

  public function prepareFieldBox()
  {
    foreach ($this->config['columns'] as $columnName => $columnPara)
    {
      if (!isset($columnPara['type']) || $columnPara['type'] == $this->formType)
      {
        if (isset($columnPara['needBox']))
        {
          $this->fields[$columnName]['needBox'] = $columnPara['needBox'];
        }
        else
        {
          $this->fields[$columnName]['needBox'] = true;
        }
      }
    }
  }

  public function prepareFieldTitle()
  {
    foreach ($this->config['columns'] as $columnName => $columnPara)
    {
      if (!isset($columnPara['type']) || $columnPara['type'] == $this->formType)
      {
        if (isset($columnPara['needTitle']) && $columnPara['needTitle'] === false)
        {
          $this->fields[$columnName]['title'] = '';
        }
        else if (isset($columnPara['title']))
        {
          $this->fields[$columnName]['title'] = $columnPara['title'];
        }
        else
        {
          $this->fields[$columnName]['title'] = ConvertTool::toDisplay($columnName);
        }
      }
    }
  }

  public function prepareFieldContent()
  {
    foreach ($this->config['columns'] as $columnName => $columnPara)
    {
      if (!isset($columnPara['type']) || $columnPara['type'] == $this->formType)
      {
        $this->fields[$columnName]['content'] = $this->formatCell($columnName, $columnPara);
      }
    }
  }

  public function formatCell($columnName, $columnPara)
  {
    if (isset($this->config['columns'][$columnName]) && isset($this->config['columns'][$columnName]['function']))
    {
      $functions = $this->config['columns'][$columnName]['function'];
      foreach ($functions as $functionName => $parameters)
      {
        foreach ($parameters as $paraKey => $paraValue)
        {
          $parameters[$paraKey] = $this->replaceTokenWithDefaultValue($paraValue);
        }

        //$parameters = $this->replaceTokenWithDefaultValue($parameters);
//        foreach ($parameters as $key => $parameter)
//        {
//          if ($this->formType == 'edit')
//          {
//            $parameters[$key] = preg_replace('/%([\w:]+)%/e', "\$this->replaceWithEditValue('\\1')", $parameter);
//          }
//          else
//          {
//            $parameters[$key] = preg_replace('/%([\w:]+)%/e', "", $parameter);
//          }
//        }

        $row[$functionName] = call_user_func_array($functionName, $parameters);
        $returnValue = $row[$functionName];
      }
    }
    else
    {
      $returnValue = '';
    }

    return $returnValue;
  }

  private function replaceTokenWithDefaultValue($value)
  {
    if (is_array($value))
    {
      foreach ($value as $subKey => $subValue)
      {
        $value[$subKey] = $this->replaceTokenWithDefaultValue($subValue);
      }
    }
    else
    {
      if (!strstr($value, '%'))
      {
        return $value;
      }

      if (preg_match('/%([\w:]+)%/e', $value, $matchs))
      {
        if ($this->formType == 'edit')
        {
          if (!isset($this->editValue[$matchs[1]]))
          {
            return $value;
          }
        }

        if (is_string($this->editValue[$matchs[1]]))
        {
          if ($this->formType == 'edit')
          {
            return str_replace($matchs[0], $this->editValue[$matchs[1]], $value);
          }
          else
          {
            return str_replace($matchs[0], '', $value);
          }
        }
        else
        {
          if ($this->formType == 'edit')
          {
            return $this->editValue[$matchs[1]];
          }
          else
          {
            return '';
          }
        }
      }
    }

    return $value;
  }

//  private function replaceWithEditValue($name)
//  {
//    if (isset($this->editValue[$name]))
//    {
//      return $this->editValue[$name];
//    }
//    else
//    {
//      return '';
//    }
//  }
}