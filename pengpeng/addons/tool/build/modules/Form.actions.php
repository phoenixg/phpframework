<?php
class FormActions extends nbAction
{
  public function submitAction()
  {
    $configs = nbAppHelper::getCurrentAppConfig('item', __FILE__);
    $buildNameSpace = $this->request->getPost('buildNameSpace');

    $currentConfig = $configs[$buildNameSpace];

    $this->title = $currentConfig['title'];

    $fromPath = $currentConfig['fromPath'];
    $toPath = $currentConfig['toPath'];

    if (isset($currentConfig['fromPara']))
    {
      foreach ($currentConfig['fromPara'] as $paraKey => $fromPara)
      {
        $parameters[$paraKey] = $this->request->getPost($paraKey);
      }
    }
    else
    {
      $parameters = array();
    }

    if (isset($currentConfig['block']) && $block = $currentConfig['block'])
    {
      $this->includeBlock($block);
    }

    $generate = new nbGenerate();
    foreach ((array)$currentConfig['fromPath'] as $key => $fromPath)
    {
      if (is_file($fromPath.'build.php'))
      {
        $parameters = include($fromPath.'build.php');
      }

      $generate->generateFolder($fromPath, $currentConfig['toPath'][$key], $parameters, array('ignoreFile' => 'build.php'));
    }
  }
}