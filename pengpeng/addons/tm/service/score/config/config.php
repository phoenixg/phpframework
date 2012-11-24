<?php
/**
 * don't copy the config content here, try to copy from /cache/config/config.php
 */
$config['needCount'] = true;
$config['needTotalScoreCount'] = false;
$config['messageNoEnoughScore'] = "没有足够的积分";

$config['scoreStrategy'] = array(
  'name1' => array(
    'score' => '10',
    'limitStrategy' => 'name'),
  'lottery1' => array(
    'score' => 50,
  ),
);
?>
