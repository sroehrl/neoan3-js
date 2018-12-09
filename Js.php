<?php


namespace Neoan3\Apps;
use Neoan3\Apps\Ops;

/**
 * Class Js
 * @package Neoan3\Apps
 */
class Js {

    /**
     * @var string
     */
    public static $toString='{{next}}';
    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * js constructor.
     */
    function __construct () { }

    /**
     * @return js|null
     */
    public static function _ ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
            self::then('(function(){ {{next}} })()');
        }

        return self::$_instance;
    }

    /**
     * @param $selector
     * @param $on
     * @return null
     */
    static function __($selector, $on){
        return self::bind($selector,$on);
    }

    /**
     * @param $selector
     * @param $on
     * @return null
     */
    static function bind($selector, $on){
        self::then('document.querySelector(\''.$selector.'\').addEventListener(\''.$on.'\',{{next}});');
        return self::$_instance;
    }

    /**
     * @return null
     */
    static function fn(){
        $cString = '';
        $args = func_get_args();
        foreach ($args as $i=> $arg){
            $cString .= ($i>0?', ':'').$arg;
        }
        self::then('function('.$cString.'){{{next}}}');
        return self::$_instance;
    }

    /**
     * @return null
     */
    static function nfn(){
        $cString = '';
        $name = '';
        $args = func_get_args();
        foreach ($args as $i=> $arg){
            if($i==0){
                $name = $arg;
            } else {
                $cString .= ($i>0?', ':'').$arg;
            }

        }
        self::then('function '.$name.'('.$cString.'){{{next}}}');
        return self::$_instance;
    }

    /**
     * @param string $string
     * @return null
     */
    static function then($string){
        self::$toString = Ops::embrace(self::$toString,['next'=>$string]);
        return self::$_instance;
    }

    /**
     * @return string
     */
    static function out(){
        $save = self::$toString;
        self::$toString = '{{next}}';
        self::$_instance = null;
        return $save . ';';
    }

    /**
     * @return null
     */
    static function next(){
        self::$toString = substr(self::$toString,0,-4). '{{next}} })()';
        return self::$_instance;
    }

    /**
     * @param $evaluate
     * @return null
     */
    static function if($evaluate){
        self::then('if('.$evaluate.'){ {{next}} }');
        return self::$_instance;
    }

    /**
     * @param $evaluate
     * @return null
     */
    static function elseif($evaluate){
        self::then('else if('.$evaluate.'){ {{next}} }');
        return self::$_instance;
    }
}