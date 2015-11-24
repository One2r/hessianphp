<?php
namespace HessianPHP;
/**
 * Results from parsing a call to a local object
 * @author vsayajin
 *
 */
class HessianCall{
    var $method;
    var $arguments = array();

    function __construct($method='', $arguments=array()){
        $this->method = $method;
        $this->arguments = $arguments;
    }
}