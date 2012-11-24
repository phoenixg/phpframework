<?php
class DefaultActions extends nbAction
{
  public function indexAction()
  {

  }

  public function submitAction()
  {
    $appName = $this->request->getPost('appName');

    $zip = new Zipper;
    if ($zip->open(HTTP_ROOT.$appName.'.zip', ZipArchive::CREATE) === TRUE)
    {
      $appPathRoot = nbAppHelper::getAppNoteRoot($appName);

      $zip->addDir(substr(nbAppHelper::getAppRoot($appName), 0, -1), $appPathRoot);

      $appWebRoot = HTTP_ROOT.str_replace('-', DIRECTORY_SEPARATOR, $appName);

      if (is_dir($appWebRoot))
      {
        $zip->addDir($appWebRoot, HTTP_ROOT, nbAppHelper::getAppNotePath($appName, '/').'web/');
      }

      $zip->close();
      echo 'ok, <a href="'.URL_ROOT.$appName.'.zip">download</a>';
    } else {
      echo 'failed';
    }
    exit;
  }
}