<?php
$str = 'static::$items["application"]["aaa"]["ddd"]["eee"]["out"]';
echo substr($str, 0 , strrpos($str, '['));

var_dump(eval('return  "whatever";'));