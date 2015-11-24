<?php
namespace HessianPHP;
/**
 * Remote Exception, nuff said
 * @author vsayajin
 *
 */
class HessianFault extends \Exception{
    var $detail;

    function __construct($message = '', $code = '', $detail = null){
        $this->message = $message;
        $this->code = $code;
        $this->detail = $detail;
    }
}