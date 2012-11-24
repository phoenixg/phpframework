<?php

class TMWidgetService
{
  public static function insertWidgetHistory($limitStrategy, $scoreStrategy, $awardStrategy)
  {
    $dao = new TMWidgetDao;
    $dao->insertWidgetHistory($limitStrategy, $scoreStrategy, $awardStrategy);
  }
}