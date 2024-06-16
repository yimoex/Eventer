<?php
namespace Eventer\Ext\Network;

use Eventer\Libs\Network\ConnectionPool;

class AsyncHttpConnection extends HttpConnection{

    public $id = NULL;

    public function connect(){
        if(!$this -> _connect()) return false;
        $this -> id = ConnectionPool::push($this);
    }
}
