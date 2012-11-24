<?php
class Checker extends nbAction
{
  /**
   * Keep the message
   *
   * @param string $file        the address of the file
   * @param string $message     the messgae need to display
   * @param int $lineNum        the address of the file
   */
  protected function keepMessage($file, $message, $lineNum)
  {
    if (!is_array($lineNum))
    {
      $lineNum = array($lineNum);
    }

    foreach ($lineNum as $line)
    {
      $this->errorMeaasge[] = realpath($file).': on line '.$line.' : '.$message."\n";
    }
  }

  protected function checkRegRule($file, $fileContent)
  {
    foreach ($this->checkRule as $rule)
    {
      $result = preg_match($rule['reg'], $fileContent, $matchs);


      if ($result)
      {
        $lineNum = array();
        $subContent = preg_split($rule['reg'], $fileContent);

        $adjustLine = isset($rule['adjustLine']) ? $rule['adjustLine'] : 0;

        $baseOffline = isset($rule['baseOffline']) ? $rule['baseOffline'] : 0;

        foreach ($subContent as $key => $value)
        {
          if ($key > 0)
          {
            $lineNum[$key] = substr_count($value, "\n") + $lineNum[$key - 1] + $adjustLine;
          }
          else
          {
            $lineNum[$key] = substr_count($value, "\n") + 1 + $baseOffline;
          }
        }

        array_pop($lineNum);
        $this->keepMessage($file, $rule['message'], $lineNum);
      }
    }
  }

  /**
   * display the message
   */
  protected function displayMessage()
  {
    if (isset($this->errorMeaasge))
    {
      foreach ($this->errorMeaasge as $message)
      {
        echo $message."<br />";
      }
    }
  }

  protected function addRule($info, $reg, $message, $adjustLine = 0, $baseOffline = 0)
  {
    $this->checkRule[] = array(
      'reg' => $reg,
      'message' => $message,
      'adjustLine' => $adjustLine,
      'baseOffline' => $baseOffline,
    );
  }
}