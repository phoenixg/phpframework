<?php
class IndentationActions extends Checker
{
  public function defaultAction()
  {
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
   * replace the comment with '', and replace the string with "";
   *
   * @param unknown_t'ype $value
   * @return string
   */
  private function replace($value)
  {
    // one line string or comment
    if (!preg_match('/\n/', $value))
    {
      preg_match('/^ */', $value, $matchs);
      return "{$matchs[0]}\"\"";
    }
    else
    {
      $lines= preg_split('/\n/', $value);

      for ($i = 0; $i < count($lines); $i++)
      {
        // start with /*
        if (preg_match('/^ *(?=\/\*)/', $value, $matchs))
        {
          // the first line of /** */ comment
          if ($i == 0)
          {
            $temp[] = "{$matchs[0]}''";
          }
          else
          {
            preg_match('/ *\*/', $lines[$i], $matchs);
            if (isset($matchs[0]))
            {
              $matchs[0] = preg_replace('/ $/', '', $matchs[0]);
              $temp[] = "{$matchs[0]}''";
            }
          }
        }
        else
        {
          preg_match('/^ */', $lines[$i], $matchs);
          $temp[] = "{$matchs[0]}\"\"";
        }
      }

      return implode("\n", $temp);
    }
  }

  private function replaceParentheses($value, $head, $tail)
  {
    if (!preg_match('/\n/', $value))
    {
      return $head.'()'.$tail;
    }
    else
    {
      $lineNum = count(preg_split('/\n/', $value));

      for ($i = 0; $i < $lineNum; $i++)
      {
        $temp[] = '';
      }

      return $head."()".$tail.implode("\n", $temp);
    }
  }

  /**
   * check one file's code indentation
   *
   * @param string $file         the address of the file
   * @param string $fileContent  the name of the file
   */
  protected function doCheck($file)
  {
    //    preg_match('/\([^)]*/', '$defaultOption = array(type => text', $matchs);
    //    p($matchs);

    $fileContent = file_get_contents($file);

    /**
     * remove all '', "", /* *\/ //
     * @var unknown_type
     */
    $fileContent = preg_replace("/<<<(\w*).*(\\1);|\"\"|''|'.*((\\\\\\\\)+'|[^\\\\]')|\".*((\\\\\\\\)+\"|[^\\\\]\")|(\t| )*\/\*.*\*\/|(\t| )*\/\/.*(?=\n)/Use", "\$this->replace('\\0')", $fileContent);

    $fileContent = preg_replace("/([^\n]{30,})\(((?>[^()]+)|(?R))*\)([^\n]*)/e", "\$this->replaceParentheses('\\0', '\\1', '\\3')", $fileContent);
//p($fileContent);
    $contentArray = preg_split('/\n/', $fileContent);

    $indentationThisLineShould = 0;
    $indentationNextLineShould = 0;
    $indentationThisLine = 0;
    $lineNum = 0;
    $errorNumInThisFile = 0;
    $inColon = false;

    foreach ($contentArray as $key => $row)
    {
      $lineNum++;

      // skip blank line
      if (preg_match('/^$/', $row, $matchs))
      {
        continue;
      }

      /**
       *  for the situation like this:
       *
       *  $string = '<script>
       *  var loadCss = function(url)
       *  {
       *    var node = document.createElement("link");
       *    node.type = "text/css";
       *    node.rel = "stylesheet";
       *    node.media = "all";
       *    node.href = url;
       *    top.document.getElementsByTagName("head")[0].appendChild(node);
       *  }'."\n";
       */
      // skip string or line have no content
      if (preg_match('/^ *\"\"[. ";]*$/', $row, $matchs))
      {
        continue;
      }

      /**
       * $port_string = (null != $config['system_' . $protocol . '_port'])
       *      ? ':' . $config['system_' . $protocol . '_port']
       *      : NULL;
       */
      if (preg_match('/^ *\?|^ *:/', $row, $matchs))
      {
        continue;
      }

      $indentationThisLineShould = $indentationNextLineShould;

      if (preg_match('/^ *(?!\*)/', $row, $matchs))
      {
        $indentationThisLine = strlen($matchs[0]);
      }
      else
      {
        $indentationThisLine = 0;
      }

      // deal { and }
      if (preg_match('/{$/', $row, $matchs))
      {
        $indentationNextLineShould += 2;
      }
      if (preg_match('/}$/', $row, $matchs))
      {
        $indentationThisLineShould -= 2;
        $indentationNextLineShould -= 2;
      }

      // deal ( and )
      if (count(preg_split('/\(/', $row)) > count(preg_split('/\)/', $row)))
      {
        $indentationNextLineShould += 2;
      }
      else if (count(preg_split('/\(/', $row)) < count(preg_split('/\)/', $row)))
      {
        $indentationNextLineShould -= 2;
        /**
         * )
         * ),
         * );
         * ));
         * )));
         * ))));
         */
        if (preg_match('/^[ )]*\)[;,]?$/', $row, $matchs))
        {
          $indentationThisLineShould -= 2;
        }
      }

      /**
       * for this condition format
       *
       * switch (gettype($value))
       * {
       *   case 'double':
       *   case 'integer':
       *     $converted = $value;
       *     break;
       *   case 'boolean':
       *     $converted = $value ? 'true' : 'false';
       *     break;
       *   default:
       *     // empty
       *     break;
       * }
       */

      if (preg_match('/(case|default).*:$/', $row, $matchs))
      {
        $inSwitch = true;
        if (!preg_match('/^ *(case|default)/', $contentArray[$key + 1]))
        {
          $indentationNextLineShould += 2;
        }
      }

      if (preg_match('/^ *break;$/', $row))
      {
        if (isset($inSwitch) && $inSwitch)
        {
          if (preg_match('/}$/', $contentArray[$key + 1]))
          {
            $indentationNextLineShould -= 2;
          }
          else if (preg_match('/^ *(case|default)/', $contentArray[$key + 1]))
          {
            $indentationNextLineShould -= 2;
          }
        }
        else
        {
          // empty
        }

        $inSwitch = false;
      }

      if ($indentationThisLine != $indentationThisLineShould)
      {
        if ($errorNumInThisFile < 5)
        {
          //p($fileContent);
          $errorNumInThisFile ++;
          $this->keepMessage($file, "indentation should be $indentationThisLineShould but now is $indentationThisLine", $lineNum);
        }
        else
        {
          return;
        }
      }
    }
  }
}