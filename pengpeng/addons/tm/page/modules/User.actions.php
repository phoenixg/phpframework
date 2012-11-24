<?php
class UserActions extends TMAction
{
  public function loginAction()
  {
    $this->url = $this->request->getGet('url');

    $tempUrl = urldecode($this->url);

    if (! preg_match('/qq\.com$/', $tempUrl) && ! preg_match('/qq\.com\//', $tempUrl))
    {
      throw new nb404Exception('only qq.com can use this jump.');
    }
  }
}