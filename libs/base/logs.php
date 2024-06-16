<?php
namespace Eventer\Libs\Base;

class Logs {

    public static $level = 0;

    public static $levels = [
        '调试',
        '信息',
        '警告',
        '错误'
    ];

    const DEBUG = 0;
    const INFO = 1;
    const WARN = 2;
    const ERROR = 3;

    public static function print($msg, $level = 0){
        if(self::$level > $level) return false;
        printf("[%s]: %s\r\n", self::getLevel($level), $msg);
        return true;
    }

    public static function getLevel($level){
        if(!isset(self::$levels[$level])) return $level;
        return self::$levels[$level];
    }

}