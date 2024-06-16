<?php
namespace Eventer\Package\YimoEx;

/**
 * 
 * Base基础模块
 * YimoEx使用的基础模块
 * 
 */
class Base {

    protected $data = [];

    protected function set($key, $value){
        $this -> data[$key] = $value;
    }

    protected function get($key){
        if(!isset($this -> data[$key])) return false;
        return $this -> data[$key];
    }


}
