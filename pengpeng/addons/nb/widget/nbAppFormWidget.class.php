<?php
class nbAppFormWidget
{
  public static function appPathSelect($name = 'appName')
  {
    $appRoots = nbConfigHelper::getConfig('nb/appRoots');
    foreach ($appRoots as $appName => $appRoot)
    {
      $selectOptions[$appName] = $appName;
    }
    return nbFormWidget::select($name, '', $selectOptions);
  }
}