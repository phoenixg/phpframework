<?php
$config['defaultModule'] = 'default';
$config['defaultAction'] = 'index';
$config['defaultLayout'] = 'default';

$config['filter'] = array(
  'nbRenderingFilter',
  'CsDebugFilter',
  'nbExecutionFilter',
);

$config['firstFilterClass'] = 'nbRenderingFilter';
$config['lastFilterClass'] = 'nbExecutionFilter';
$config['requestClass'] = 'nbRequest';
$config['responseClass'] = 'nbResponse';

