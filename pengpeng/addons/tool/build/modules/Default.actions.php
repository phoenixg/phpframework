<?php
class DefaultActions extends nbAction
{
    public function indexAction()
    {
      $configs = nbAppHelper::getCurrentAppConfig('item', __FILE__);

      foreach ($configs as $key => $itemConfig)
      {
        $configs[$key] = nbArrayHelper::bindPara($itemConfig, array(
          'title',
          'fromPath',
          'toPath',
          'fromPara',
          'description',
        ), array('', '', '', array(), ''));
      }

      foreach ($configs as $itemKey => $itemConfig)
      {
        if ($itemConfig['fromPara'])
        {
          foreach ($itemConfig['fromPara'] as $paraKey => $paraValue)
          {
            $functions = $paraValue['function'];
            foreach ($functions as $functionName => $parameters)
            {
              $configs[$itemKey]['fromPara'][$paraKey]['content'] = call_user_func_array($functionName, $parameters);
            }
          }

        }
      }

      $this->configs = $configs;
    }
}