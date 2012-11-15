<?php

require 'vendor/autoload.php';


$app = new \Slim\Slim(array(
	'debug' => true
));

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->run();
