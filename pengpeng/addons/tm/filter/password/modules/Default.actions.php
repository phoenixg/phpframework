<?php
class DefaultActions extends TMAction
{
  public function indexAction()
  {
    $this->url = $this->request->getGet('url', nbRequest::getInstance()->getHost());
  }

  public function submitAction()
  {
    $password = $this->request->getPost('password');
    $url = $this->request->getPost('url');

    if ($password == nbAppHelper::getCurrentAppConfig('password', __FILE__))
    {
      setcookie('tm_filter_password', true, null, '/', 'qq.com');
      $this->redirect($url);
    }
    else
    {
      echo 'wrong password';
      exit;
    }
  }
}