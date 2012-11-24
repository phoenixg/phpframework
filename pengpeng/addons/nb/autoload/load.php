<?php

if(!is_file(nbAppHelper::getCurrentAppConfig('saveFile', __FILE__)))
{
  $autoload = new nbAutoload();
  $autoload->execute();
  new nbLog("generate autoload for init");
}

spl_autoload_register(array('nbAutoloadHelper', 'getClassPath'));