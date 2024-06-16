<?php
namespace Eventer\Libs\Network;

use Eventer\Libs\Base\Buffer;

class TcpConnection extends Connection {

    public function __construct($addr, $port){
        $this -> addr = 'tcp://' . $addr . ':' . $port;
        $this -> buffer = new Buffer('');
    }

    public function connect(){
        if(!$this -> _connect()) return false;
        if(!is_object($this -> onMessage)) return false;
        while(1){
            $rec = $this -> read();
            if($rec === false || $this -> isTimeout()){
                $this -> event('Timeout', $this);
                $this -> close();
                return false;
            }
            if($this -> isDataEnd() === false || $this -> buffer -> getLength() == 0) continue;
            ($this -> onMessage)($this, $this -> response());
            $this -> buffer -> clean();
            usleep(500);
        }
    }

}
