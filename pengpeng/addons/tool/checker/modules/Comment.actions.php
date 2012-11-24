<?php
class CommentActions extends Checker
{
  public function defaultAction()
  {
    $rules = nbAppHelper::getCurrentAppConfig('commentRules', __FILE__);

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

  public function prepareRule()
  {
    $this->addRule('methodNeedComment', "/(?<!\/)\n".CHECKER_ONE_SPACE."*".CHECKER_METHOD_KEYWORD."function/", 'all class methods need comment', 1, 1);
  }

  /**
   * check one file's code indentation
   *
   * @param string $file         the address of the file
   * @param string $fileContent  the name of the file
   */
  protected function doCheck($file)
  {
    $fileContent = file_get_contents($file);

    $this->checkRegRule($file, $fileContent);

    $contentArray = preg_split('/\n/', $fileContent);

    $lineNum = 0;

    foreach ($contentArray as $key => $row)
    {

      $lineNum++;

      /**
       * match the end of command
       */
      if (preg_match('/ *\*\//', $row))
      {
        $i = $key;
        $totalCommandParam = $totalReturn = array();

        /**
         * match the method header
         */
        preg_match('/^.*function ([a-zA-Z0-9]+)\((.*)\)$/', $contentArray[$key + 1], $matchHeader);

        /**
         * match the start of command
         */
        while (!preg_match('/ *\/\*\*/', $contentArray[$i], $match))
        {
          if ($i > 0)
          {
            $i--;
          }
          else
          {
            break;
          }
          /**
           * keep param information in command
           */
          if (preg_match('/@param/', $contentArray[$i]))
          {
            if (preg_match('/@param +([^ ]*) +([^ ]*)/', $contentArray[$i], $subMatch))
            {
              $totalCommandParam[$subMatch[2]] = array(
                'line' => $i + 2,
                'type' => $subMatch[1],
                'name' => $subMatch[2],
              );
            }
            else
            {
              $this->keepMessage($file, 'the param type should be "@param Type $variableName description"', $i + 1);
              continue;
            }
          }

          /**
           * keep return information in command
           */
          if (preg_match('/@return +([^ ]*)/', $contentArray[$i], $subMatch))
          {
            $totalReturn[] = array(
              'line' => $i + 1,
              'type' => $subMatch[1],
            );
          }
        }

        if (!$matchHeader)
        {
          // this is const;
          // hack
          continue;
        }
        $methodParam = preg_split('/, ?/', $matchHeader[2]);
        $totalMethodParam = array();

        /**
         * keep param information in method head
         */
        if ('' != $matchHeader[2])
        {
          foreach ($methodParam as $value)
          {
            /**
             * Type $value = defaultValue
             */
            if (preg_match('/([a-zA-Z0-9]+) &?(\$[a-zA-Z0-9]+) ?=?/', $value, $subMatch))
            {
              $totalMethodParam[$subMatch[2]] = array(
                'line' => $key + 2,
                'type' => $subMatch[1],
                'name' => $subMatch[2],
              );
            }

            /**
             * $value = defaultValue
             */
            else if (preg_match('/&?(\$[a-zA-Z0-9]+) ?= ?.*/', $value, $subMatch))
            {
              $totalMethodParam[$subMatch[1]] = array(
                'line' => $key + 2,
                'type' => '',
                'name' => $subMatch[1],
              );
            }

            /**
             * $value
             */
            else
            {
              if ('&' == $value[0])
              {
                $value = substr($value, 1);
              }
              $totalMethodParam[$value] = array(
                'line' => $key + 2,
                'type' => '',
                'name' => $value,
              );
            }
          }
        }

        if (empty($totalReturn))
        {
          $this->keepMessage($file, 'must use @return in command', $key + 2);
        }

        /**
         * check the style
         */
        if (count($totalCommandParam) != count($totalMethodParam))
        {
          $this->keepMessage($file, 'the param number is not match between command and method defineded', $key + 2);
        }
        else
        {
          foreach ($totalMethodParam as $subKey => $subValue)
          {
            if (!isset($totalCommandParam[$subKey]))
            {
              $this->keepMessage($file, $subKey." is not in command as '@param Type ...'", $key + 2);
              continue;
            }

            if ('unknown_type' == $totalCommandParam[$subKey]['type'])
            {
              $this->keepMessage($file, 'the param type cannot be "unknown_type" in command', $totalCommandParam[$subKey]['line'] - 1);
              continue;
            }

            if (!in_array($totalCommandParam[$subKey]['type'], array('mixed', 'int', 'string', 'boolean')) && $subValue['type'] != $totalCommandParam[$subKey]['type'])
            {
              // type hint would case problem in an abstract method @see: \symfony-1.1.4\lib\action\sfComponent.class.php, because symfony didn't do type hint.
              // hack here
              if ('$request' == $subValue['name'])
              {
                continue;
              }
              // remove the type hint message here
              //$this->keepMessage($file, 'the varible type in command is not the same as the one defineded in method', $key + 2);
            }
          }
        }
      }
    }
  }

}