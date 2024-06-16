<?php
namespace Eventer\Package\YimoEx;

use Eventer\Ext\Network\AsyncHttpConnection;
use Eventer\Ext\Network\HttpConnection;

/**
 * 
 * BaseHttp基础模块
 * YimoEx使用的HTTP基础模块
 * 
 */
class BaseHttp {

    public $request_type = 'async';
    private $cookie = '';

    protected function request($url, array $param){
        if($this -> request_type === 'async'){
            return new AsyncHttpConnection($url . '?' . http_build_query($param));
        }else{
            return new HttpConnection($url . '?' . http_build_query($param));
        }
    }

    protected function build($url, $param, $on){
        $http = $this -> request($url, $param);
        $http -> onMessage = $on;
        if($this -> cookie != NULL){
            $http -> request() -> setCookie($this -> cookie);
        }
        $http -> connect();
    }

    public function setCookie(string $cookie){
        $this -> cookie = $cookie;
    }

}