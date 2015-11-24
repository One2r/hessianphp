<?php
namespace HessianPHP;
use HessianPHP\Interfaces\HessianIgnoreCode;
/**
 * Hold information on declared classes in the incoming payload
 * @author vsayajin
 */
class HessianClassDef implements HessianIgnoreCode{
    var $type;
    var $remoteType;
    var $props = array();
}