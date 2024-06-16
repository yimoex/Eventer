<?php
namespace Eventer\Package\YimoEx;

use Eventer\Ext\Network\AsyncHttpConnection;

/*
 * UploadPacket(上报网络包)
 * 优点: 传统网络包会存在接受相应的过程,而上报网络包不关心是否上报成功
 * 只注重上报,能够节省很多网络开销
 * 
 * (本地环境中)上报包的速度是AsyncHttpConnection的8倍
 */
class UploadPacket {

    public $connection;
    public function __construct($addr){
        $this -> connection = new AsyncHttpConnection($addr);
    }

    public function run(object $request_call){
        $http = $this -> connection -> request();
        $request_call($http);
        $this -> connection -> onConnect = function($connection) use ($http){
            $packet = $http -> make();
            $error = 0;
            $maxError = 5; //最大失败次数
            do {
                if($error > $maxError) return false;
                $rec = $connection -> send($packet);
                if($rec !== false) break;
                $error++;
            } while(1);
            $connection -> close();
        };
        $this -> connection -> connect();
    }

}
