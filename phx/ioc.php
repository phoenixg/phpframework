<?php

IoC::register('photo', function() {  
   $photo = new Photo;  
   $photo->setDB('...');  
   $photo->setConfig('...');  
   return $photo;  
});  
// Fetch new photo instance with dependencies set  
$photo = IoC::resolve('photo');  

