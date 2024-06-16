<?php
namespace Eventer\Libs\Base;

class Buffer {
    
    public $data = '';
    public $dataLength = 0;

    public function __construct($data = ''){
        $this -> data = $data;
        $this -> dataLength = strlen($data);
    }

    public function add($data){
        if($data == NULL) return false;
        $this -> data .= $data;
        $this -> dataLength += strlen($data);
        return $this;
    }

    public function get(){
        return $this -> data;
    }

    public function exec(object $func){
        return $func($this -> data);
    }

    public function getLength(){
        return $this -> dataLength;
    }

    public function clean(){
        $this -> data = '';
        $this -> dataLength = 0;
        return true;
    }

    public function isEmpty(){
        return $this -> dataLength === 0;
    }

}