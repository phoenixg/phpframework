<?php
class StyleActions extends Checker
{
  public function defaultAction()
  {
    $rules = nbAppHelper::getCurrentAppConfig('rules', __FILE__);

    foreach ($rules as $key => $rule)
    {
      $rule[2] = isset($rule[2]) ? $rule[2] : null;
      $rule[3] = isset($rule[3]) ? $rule[3] : null;
      $this->addRule($key, $rule[0], $rule[1], $rule[2], $rule[3]);
    }

    $finderPara = nbAppHelper::getCurrentAppConfig('finderPara', __FILE__);
    $finder = new nbFinder();
    foreach ($finder->execute($finderPara) as $file)
    {
      $this->doCheck($file);
    }

    $this->displayMessage();
    exit;
  }

  /**
   * Enter description here...
   *
   * @param string $file
   */
  protected function doCheck($file)
  {
    $fileContent = file_get_contents($file);

    /**
     * remove all '', "", /* *\/ //
     * @var unknown_type
     */
    $fileContent = preg_replace("/''|\"\"|'.*((\\\\\\\\)+'|[^\\\\]')|\".*((\\\\\\\\)+\"|[^\\\\]\")|(\t| )*\/\*.*\*\/|(\t| )*\/\/.*(?=\n)/Use", "\$this->replace('\\0')", $fileContent);

    $this->checkRegRule($file, $fileContent);
  }

  /**
   *
   * @p'aram unknown_t'ype $value
   * @return string
   */
  private function replace($value)
  {
    if (!preg_match('/\n/', $value))
    {
      return "''";
    }
    else
    {
      $lineNum = count(preg_split('/\n/', $value));

      for ($i = 0; $i < $lineNum; $i++)
      {
        $temp[] = "''";
      }

      return implode("\n", $temp);
    }
  }
}