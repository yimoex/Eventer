<?php
namespace Eventer\Libs\Base;

class CacheNode {

    public $id = '';
    private $data = [];
    private $timeout = 300; //0为不过期
    private $update_time = 0;
    private $locked = false; //写锁

    public function __construct($id){
        $this -> id = $id;
        $this -> update();
    }

    public function push($value){
        if($this -> locked) return false;
        $this -> data[] = $value;
        return true;
    }

    public function append($key, $value){
        if($this -> locked) return false;
        $this -> data[$key] .= $value;
        $this -> update();
        return true;
    }

    public function write($key, $value){
        if($this -> locked) return false;
        $this -> data[$key] = $value;
        $this -> update();
        return true;
    }

    public function read($key){
        if(!isset($this -> data[$key])) return false;
        return $this -> data[$key];
    }

    public function getAll(){
        return $this -> data;
    }

    public function dataSize(){
        return count($this -> data);
    }

    public function update(){
        $this -> update_time = microtime(true);
    }

    public function lock(){
        $this -> locked = true;
        return true;
    }

    public function isTimeout(){
        if($this -> timeout === 0) return false;
        return (int)(microtime(true) - $this -> update_time) > $this -> timeout;
    }

    public function setTimeout(int $timeout){
        $this -> timeout = $timeout;
        return true;
    }

}