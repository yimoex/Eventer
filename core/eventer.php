<?php
namespace Eventer\Core;

use Eventer\Libs\Network\ConnectionPool;

class Eventer {

    public $ticks = 10000;
    public $sleep_time = 1;
    public $_tasks = [];

    public $onStart = NULL;
    public $onExit = NULL;

    public function __construct($param = []){
        $this -> ticks = $param['ticks'] ?? 20;
        define('ROOT', dirname(__DIR__));
    }

    /**
     * Summary of run
     * @return never
     */
    public function run(){
        $ticks = $this -> ticks;
        $sleep = $ticks == 0 ? 10 : 1 / $ticks * 1000000;
        $this -> loadFunction();
        $this -> findApp();
        $sleep = 0;
        $this -> event('Start');
        while(1){
            ConnectionPool::run();
            foreach($this -> _tasks as $id => $task){
                $t =  microtime(true) - $task -> lasttime;
                if($t < $task -> timer) continue;
                $task -> run();
                if($task -> counts === 0) unset($this -> _tasks[$id]);
            }
            usleep($sleep);
        }
    }

    public function findApp(){
        $apps = require(ROOT . '/app/app.php');
        if($apps == NULL) return false;
		foreach($apps as $app){
			$app::run($this);
		}
    }

    public function loadFunction(){
        return require(ROOT . '/core/function.php');
    }

    public function register($caller, $param = []){
        $this -> _tasks[] = new Event($caller, $param);
    }

    public function event($id, ...$param){
        $id = 'on' . $id;
        $obj = $this -> $id;
        if(!is_object($obj)) return;
        $obj(...$param);
    }

}
