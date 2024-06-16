<?php
include 'core/autoload.php';

use Eventer\Core\Loader;
use Eventer\Core\Eventer;

use Eventer\Libs\Network\TcpConnection;
use Eventer\Libs\Network\AsyncTcpConnection;

use Eventer\Libs\Base\Cache;

use Eventer\Ext\Network\AsyncHttpConnection;

Loader::run();

$ev = new Eventer();
$ev -> register(function($eventer){
    $http = new AsyncHttpConnection('http://localhost:81/test.php?id=test');
    $http -> onMessage = function($http, $response){
        var_dump($response -> get());
    };
    $http -> connect();
}, ['timer' => 1]);
$ev -> run();
