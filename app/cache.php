<?php
namespace Eventer\App;

use Eventer\Libs\Base\Cache as CacheCore;

class Cache {

    public function run($eventer){
        $eventer -> register(function(){
            CacheCore::run();
        }, [
            'timer' => 1
        ]);
    }

}
