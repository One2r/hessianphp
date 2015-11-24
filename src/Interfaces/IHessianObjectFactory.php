<?php
namespace HessianPHP\Interfaces;
use HessianPHP\HessianOptions;
/**
 * Defines a contract for object creation used by the decoders
 */
interface IHessianObjectFactory{
    public function getObject($type);
    public function setOptions(HessianOptions $options);
}