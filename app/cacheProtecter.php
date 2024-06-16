<?php
namespace Eventer\App;

use Eventer\Libs\Base\Cache;

class CacheProtecter {

    public static $max = 1024 * 1024 * 1; //1MB

    public function run($eventer){
        $eventer -> register(function(){
            $count = 0;
            foreach(Cache::getAll() as $id => $node){
                $size = memCount($node);
                if($size > self::$max){
                    Cache::del($id);
                    $count += $size;
                }
            }
            $size = size_convert($count);
            var_dump('CacheProtecter: 无所谓,我会出手,清理了('.$size.')');
        }, [
            'timer' => 5
        ]);
    }

}

function memCount($var){
    $mem = memory_get_usage();
    $tmp = unserialize(serialize($var));
    return memory_get_usage() - $mem;
}