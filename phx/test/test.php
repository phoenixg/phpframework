<?php
include 'marg/marg.php';

$routes = array(
    '/' => 'home',
);

function home() {
    echo 'Hello World!';
}

Marg::run($routes);