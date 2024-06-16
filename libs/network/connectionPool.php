<?php
namespace Eventer\Libs\Network;

use Eventer\Libs\Base\Logs;

class ConnectionPool {
    
    private static $_connections = [];
    private static $_connectionSize = 0;
    public static $_connectionMax = 10240;

    public static function push(Connection $connection){
        if(self::$_connectionSize >= self::$_connectionMax) return false;
        if($connection -> isConnected === false) return false;
        $id = self::findId();
        self::$_connections[$id] = $connection;
        self::$_connectionSize++;
        return $id;
    }

    public static function shutdown($id){
        if($id == NULL){
            Logs::print('尝试关闭ID为NULL的连接', Logs::WARN);
            return false;
        }
        unset(self::$_connections[$id]);
        self::$_connectionSize--;
    }

    public static function findId(){
        return uniqid();
    }

    public static function run(){
        if(self::$_connectionSize === 0) return;
        foreach(self::$_connections as $id => $connection){
            $buffer = $connection -> buffer;
            if($connection -> isTimeout()){
                $connection -> event('Timeout', $connection);
                $connection -> close();
                self::shutdown($id);
                continue;
            }
            if($connection -> isConnected === false || ($rec = $connection -> read()) === false){
                self::shutdown($id);
                continue;
            }
            if(!$connection -> isDataEnd()) continue;
            if($buffer -> isEmpty() || !is_object($connection -> onMessage)) continue;
            ($connection -> onMessage)($connection, $connection -> response());
            
            $buffer -> clean();
        }
    }
    
}
