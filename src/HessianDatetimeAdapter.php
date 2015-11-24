<?php
namespace HessianPHP;
/**
 * Default Datetime adapter that works with the built-in Datetime class of PHP5
 * @author vsayajin
 */
class HessianDatetimeAdapter{
    public static function toObject($ts, $parser){
        $date = date('c', $ts);
        //$date = gmdate('c', $ts);
        return new \Datetime($date);
    }

    public static function writeTime($date, $writer){
        $ts = $date->format('U');
        $stream = $writer->writeDate($ts);
        return new HessianStreamResult($stream);
    }
}