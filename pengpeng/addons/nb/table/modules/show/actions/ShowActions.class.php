<?php
class ShowActions extends BaseAction
{
  public function tableAction(Request $request)
  {
    $table = $request->getGet('table');

    $this->table =  new $table;
  }

  public function listComponent(array $parameters)
  {
    if (!isset($parameters['caller']) || !$parameters['caller'])
    {
      throw new AddonException("need parameter 'caller'");
    }

    $this->table =  $parameters['table'];
  }
}