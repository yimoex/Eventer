<?php
namespace Eventer\Package\YimoEx;
use Eventer\Libs\Network\Connection;

class Caller {

    public $connection;
    public $tasks = [];

    public function __construct(Connection $connection, $start_msg = NULL){
        $this -> connection = $connection;
        if($start_msg !== NULL){
            $connection -> onConnect = function($connection) use ($start_msg){
                $connection -> send($start_msg);
            };
        }
        $tasks = &$this -> tasks;
        $connection -> onMessage = function($connection, $data) use (&$tasks){
            if($tasks == NULL){
                $connection -> close();
            }else{
                $task = array_pop($tasks);
                ($task -> response)($data);
                $connection -> send($task -> msg);
            }
        };
    }

    public function addTask($func, $msg){
        $t = new \stdClass;
        $t -> response = $func;
        $t -> msg = $msg;
        array_push($this -> tasks, $t);
        return $this;
    }

}
