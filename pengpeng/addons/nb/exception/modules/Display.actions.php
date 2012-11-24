<?php
class DisplayActions extends nbAction
{
  public function indexAction()
  {
    $key = $this->request->getGet('message');

    $allExceptionInfo = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    foreach ($allExceptionInfo as $exceptionInfo)
    {
      if ($exceptionInfo['key'] == $key)
      {
        foreach ($this->request->getGet('p') as $key => $value)
        {
          $exceptionInfo['suggest'] = str_replace("%$key%", $value, $exceptionInfo['suggest']);
        }


        $this->exceptionInfo = $exceptionInfo;
      }
    }
  }
}