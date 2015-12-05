<?php

require __DIR__ . '/../../../../vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$r3 = new Respect\Rest\Router;

$r3->get('/ping', function() {
    return 'pong';
});

$matchRules = [
    'default' => 'default',
    'tricae' => 'tricae',
    'ws/seller' => 'walmart',
];

foreach ($matchRules as $match => $fileName) {
    if(strstr($_SERVER['REQUEST_URI'], $match)) {
        require __DIR__.'/'.$fileName.'.php';
        break;
    }
}
