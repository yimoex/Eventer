<?php
namespace Eventer\Libs\Network;

use Eventer\Libs\Base\Buffer;

class AsyncTcpConnection extends Connection {

    public $id = NULL;

    public function __construct($addr, $port){
        $this -> addr = 'tcp://' . $addr . ':' . $port;
        $this -> buffer = new Buffer('');
        $this -> create_time = microtime(true);
    }

    public function connect(){
        if(!$this -> _connect()) return false;
        $this -> id = ConnectionPool::push($this); //推送到连接池管理
    }

}
