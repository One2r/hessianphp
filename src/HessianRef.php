<?php
namespace HessianPHP;
/**
 * Represents an index to a reference. This hack is necessary for handling arrays
 * references
 * @author vsayajin
 *
 */
class HessianRef{
    var $index;

    static function isRef($val){
        return $val instanceof HessianRef;
    }

    static function getIndex($list){
        return new HessianRef($list);
    }

    function __construct($list){
        if(is_array($list))
            $this->index = count($list) - 1;
        else $this->index = $list;
    }
}
