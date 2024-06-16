<?php
namespace Eventer\Libs\Base;

class BetterString {

    protected $data = '';
    protected $raw_length = 0;
    protected $length = 0;
    private $isGz = false;

    public function add($value){
        $this -> data .= $value;
        $this -> length += strlen($value);
        return $this;
    }

    public function isGz(){
        return (bool)$this -> isGz;
    }

    public function gz(){
        $this -> isGz = true;
        $this -> data = gzcompress($this -> data);
        $this -> raw_length = $this -> length;
        $this -> length = strlen($this -> data);
    }

    public function gzun(){
        $this -> isGz = false;
        $this -> data = gzuncompress($this -> data);
        $this -> length = $this -> raw_length;
        $this -> raw_length = 0;
    }

}
