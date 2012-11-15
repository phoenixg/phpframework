
<?php
/**
 * http://twitto.org/
 * 基于PHP 5.3新特性, 且仅有140个字符的超微框架
 * by Symfony 的作者： Fabien Potencier
 */

require __DIR__.'/c.php';
if (!is_callable($c = @$_GET['c'] ?: function() { echo 'Woah!'; }))
  throw new Exception('Error');
$c();