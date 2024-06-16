<?php
namespace Eventer\Libs\Base;

class Cache {

    public $update_type = 0x11; //第一个是读，第二个是写(1为会触发update)
    private static $nodes = [];
    private static $node_counts = 0;
  
    public static function create($id, $timeout = 300) : CacheNode {
        $node = new CacheNode($id);
        $node -> setTimeout($timeout);
        self::$nodes[$id] = $node;
        self::$node_counts++;
        return $node;
    }

    public static function get($id){
        if(!isset(self::$nodes[$id])) return false;
        return self::$nodes[$id];
    }

    public static function getAll(){
        return self::$nodes;
    }

    public static function getCount(){
        return self::$node_counts;
    }

    public static function del($id){
        if(!isset(self::$nodes[$id])) return false;
        unset(self::$nodes[$id]);
        self::$node_counts--;
        return true;
    }

    public static function run(){
        if(self::$node_counts === 0) return false;
        $waiter = [];
        foreach(self::$nodes as $id => $node){
            if($node -> isTimeout()){
                $waiter[] = $id;
            }
        }
        if($waiter === []) return true;
        foreach($waiter as $wait){
            unset(self::$nodes[$wait]);
        }
    }

    public static function export($cache_id, $file_id = ''){
        if($file_id == NULL) $file_id = uniqid();
        $node = self::get($cache_id);
        if(!$node) return false;
        $fp = fopen(ROOT . '/cache/' . $file_id, 'w+');
        $rec = fwrite($fp, serialize($node));
        fclose($fp);
        if(!$rec) return false;
        return $file_id;
    }

    public static function import($id){
        $file = ROOT . '/cache/' . $id;
        $fp = fopen($file, 'r');
        if(!$fp) return false;
        $buffer = fread($fp, filesize($file));
        if(!$buffer) return false;
        fclose($fp);
        self::$nodes[$id] = unserialize($buffer);
        self::$node_counts++;
        return true;
    }

}
