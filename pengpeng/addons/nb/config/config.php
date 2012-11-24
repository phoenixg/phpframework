<?php
$config['defaultApp'] = 'default';

$config['configAlias'] = array();

$config['nb-exception/item'][] = array(
  'key' => 'this app called %appName%.php is not allowed to view',
  'suggest' => "add %appName%.php to \$config['nb/doorOpened']",
)





?>


