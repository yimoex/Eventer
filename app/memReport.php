<?php
namespace Eventer\App;

use Eventer\Libs\Base\Cache;

class MemReport {

    public function run($eventer){
        $cache = Cache::create('memReport', 0);
        $cache -> write('start_mem', memory_get_usage());
        $eventer -> register(function(){
            $c = Cache::get('memReport');
            $mem = memory_get_usage() - (float)($c -> read('start_mem'));
            var_dump('内存使用: ' . size_convert($mem));
        }, [
            'timer' => 1
        ]);
    }

}

function size_convert($size, $round = 2){
	$func = function($_size, $p) use ($size, $round){
		if($size > $_size) return round($size / $_size, $round) . $p;
		return false;
	};
	$arr = [
		'GB' => 1073741800,
		'MB' => 1048576,
		'KB' => 1024,
	];
	foreach($arr as $p => $_size){
		$rec = $func($_size, $p);
		if($rec !== false) return $rec;
	}
	return round($size, $round) . 'B';
}
