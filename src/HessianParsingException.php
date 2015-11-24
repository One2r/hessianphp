<?php
namespace HessianPHP;
/**
 * Represents an error while parsing an input stream
 * @author vsayajin
 */
class HessianParsingException extends \Exception {
    // TODO custom constructors
    public $position;
    public $details;
};