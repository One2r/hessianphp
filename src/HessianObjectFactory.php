<?php
namespace HessianPHP;
use HessianPHP\Interfaces\IHessianObjectFactory;
/**
 * Default implementation of an object factory
 */

class HessianObjectFactory implements IHessianObjectFactory {
    var $options;
    public function setOptions(HessianOptions $options){
        $this->options = $options;
    }

    public function getObject($type){
        if(!class_exists($type)) {
            if(isset($this->options->strictType) && $this->options->strictType)
                throw new \Exception("Type $type cannot be found for object instantiation, check your type mappings");
            $obj = new \stdClass();
            $obj->__type = $type;
            return $obj;
        }
        return new $type();
    }
}