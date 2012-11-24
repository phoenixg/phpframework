<?php
class TMAwardHelper
{
  public static function displayAwardNameByStrategy($strategyName)
  {
    $strategies = nbAppHelper::getCurrentAppConfig('awardStrategy', __FILE__);

    // sometime the $strategyName value would be the value of scoreStrategy
    if (is_numeric($strategyName))
    {
      foreach ($strategies as $strategy)
      {
        if (isset($strategy['scoreStrategy']) && $strategy['scoreStrategy'] == $strategyName)
        {
          return $strategy['name'];
        }
      }
    }

    return $strategies[$strategyName]['name'];
  }
}