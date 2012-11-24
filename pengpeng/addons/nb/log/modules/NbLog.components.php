<?php
class NbLogComponents extends nbAction
{
  public function toolbarLogAction()
  {
    $logPath = nbHelper::getConfig('nb-log/cacheFile');
    if (file_exists($logPath))
    {
      $content = file_get_contents($logPath);
      $contentArray = preg_split('/\n\n/', $content);

      $currentLogContent = $contentArray[count($contentArray) - 1];
      $this->executeCurrentLogContent($currentLogContent);
    }
  }

  public function toolbarTimeAction()
  {

  }

  private function executeCurrentLogContent($content)
  {
    $appInfo = array();

    $contentArray = preg_split('/\n/', $content);
    foreach ($contentArray as $log)
    {
      preg_match('/\[APP\] ([^\[]*) \[/', $log, $matchs);
      $thisApp = $matchs[1];
      $appInfo[$thisApp][] = $log;
    }

    $this->appInfo = $appInfo;
  }
}