<?php
namespace HessianPHP;
/**
 * Represents a parsing rule with a type and a calling function
 * @author vsayajin
 */
class HessianParsingRule{
    var $type;
    var $func;
    var $desc;

    function __construct($type = '', $func = '', $desc = ''){
        $this->type = $type;
        $this->func = $func;
        $this->desc = $desc;
    }
}