<?php
$arr = array('a'=>array('r'=>111,'s'=>222),
	         'b'=>array('t'=>333,'u'=>444, 'v'=>array('abcdefg'=>'123456')),
	         'c'=>555
);
$str = "b.v.abcdefg";

$str = '$arr["'.str_replace('.','"]["',$str).'"]';

echo eval('return isset('.$str.')?'.$str.':"不存在";'); 

die;

$keys = explode('.', $str);

$error = "";

if(is_array($keys)){
    foreach ($keys as $n => $k){
        if(is_array($arr)&&key_exists($k, $arr)){
            $v = $arr = $arr[$k];
        }else{
            $error =  "path not found";
            break;
        }
        
    }
    
}else{
    $error = "format error";
}

if($error==''){
    echo "your value is :<br/>";
    var_dump($v);
}else{
    echo $error;
}