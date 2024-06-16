<?php
namespace Eventer\Core;

class Event {

    public $func = NULL;
    public $counts = -1;
    public $timer = 0;
    public $lasttime = 0;
    public $data = [];

    public function __construct($func, $param = []){
        $this -> func = $func;
        $this -> counts = $param['counts'] ?? -1;
        $this -> timer = $param['timer'] ?? 0;
    }

    public function run(...$param){
        ($this -> func)(...$param);
        if($this -> counts !== 0 && $this -> counts !== -1) $this -> counts--;
        $this -> lasttime = microtime(true);
    }

    public function set($id, $value){
        $this -> data[$id] = $value;
    }

    public function get($id){
        if(!isset($this -> data[$id])) return false;
        return $this -> data[$id];
    }

}
