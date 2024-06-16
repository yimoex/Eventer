<?php
namespace Eventer\Libs\Network;

use Eventer\Libs\Base\Buffer;

class Connection {

    public $addr;
    public $port;
    public $sock;
    public $buffer;
    public $start_time = 0;
    public $connect_time = 0;
    public $create_time = 0;
    public $update_time = 0;
    public $buffer_size = 8192;
    public $timeout = 3;
    public $options = [];
    public $isConnected = false;
    public $onConnect = NULL;
    public $onMessage = NULL;
    public $onTimeout = NULL;
    public $onClose = NULL;

    public function __construct($addr, $port){
        $this -> addr = $addr;
        $this -> port = $port;
        $this -> buffer = new Buffer('');
        $this -> create_time = microtime(true);
    }

    public function connect(){
        return $this -> _connect();
    }

    public function close(){
        if(is_resource($this -> sock)) fclose($this -> sock);
        if(!$this -> buffer -> isEmpty() && is_object($this -> onMessage)){
            ($this -> onMessage)($this, $this -> response());
        }
        $this -> event('Close', $this);
        $this -> isConnected = false;
		$this -> onConnect =
		$this -> onMessage =
		$this -> onTimeout =
		$this -> onClose = NULL;
        return true;
    }

    public function _connect(){
        $context = stream_context_create($this -> options);
        $sock = stream_socket_client($this -> addr, $errno, $errmsg, $this -> timeout, STREAM_CLIENT_CONNECT, $context);
        if(!$sock) return false;
        $this -> connect_time = microtime(true);
        stream_set_timeout($sock, $this -> timeout);
        stream_set_blocking($sock, 0);
        $this -> sock = $sock;
        $this -> start_time = $this -> update_time = microtime(true);
        $this -> isConnected = true;
        $this -> event('Connect', $this);
        return true;
    }

    public function read(){
        if($this -> isConnected === false) return false;
        if(feof($this -> sock)){
            $this -> close();
            return false;
        }
        $rec = fread($this -> sock, $this -> buffer_size);
        if($rec === 0 || $rec === '') return true;
        $this -> buffer -> add($rec);
        $this -> update();
        return true;
    }

    public function isDataEnd(){
        return true;
    }

	public function response(){
		return $this -> buffer -> get();
	}

    public function send($data){
        if($this -> isConnected === false) return false;
        if(feof($this -> sock)){
            $this -> close();
            return false;
        }
        $rec = fwrite($this -> sock, $data);
        $this -> update();
        return $rec;
    }

    public function checkStatus(){
        if($this -> isConnected === false) return false;
        if(feof($this -> sock)) return false;
        return true;
    }

    public function event($id, ...$param){
        $id = 'on' . $id;
        $obj = $this -> $id;
        if(!is_object($obj)) return;
        $obj(...$param);
    }

    public function isTimeout(){
        return (microtime(true) - $this -> update_time) > $this -> timeout;
    }

    protected function update(){
        $this -> update_time = microtime(true);
    }

}
