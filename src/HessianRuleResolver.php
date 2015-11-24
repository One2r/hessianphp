<?php
namespace HessianPHP;
use HessianPHP\Hessian2\Hessian2Rules;
/**
 * Contains the sequence of rules and start symbols that match the rules.
 * Resolves a rule based on a symbol and optionally checks for expected outcomes;
 * @author vsayajin
 */
class HessianRuleResolver{
    public $rules = array();
    public $symbols = array();

    function __construct($rulesFile){
        if($rulesFile)
            $this->loadRulesFromFile($rulesFile);
    }

    /**
     * Takes a symbol and resolves a parsing rule to apply. Optionally it can
     * check if the type resolved matches an expected type
     * @param string/int $symbol
     * @param string $typeExpected
     * @return HessianParsingRule rule to evaluate
     */
    function resolveSymbol($symbol, $typeExpected = ''){
        $num = ord($symbol);
        if(!isset($this->symbols[$num]))
            throw new HessianParsingException("Code not recognized: 0x".dechex($num));
        $ruleIndex = $this->symbols[$num];
        $rule = $this->rules[$ruleIndex];
        if($typeExpected){
            if(!$this->checkType($rule, $typeExpected))
                throw new HessianParsingException("Type $typeExpected expected");
        }
        return $rule;
    }

    function checkType($rule, $types){
        $checks = explode(',', $types);
        foreach($checks as $type){
            if($rule->type == trim($type))
                return true;
        }
        return false;
    }

    function loadRulesFromFile($file){
//        if(!file_exists($file))
//        	throw new HessianParsingException("Could not load parsing rules from file $file");
        //@wuhui
        $this->rules = Hessian2Rules::getRule();
        $this->symbols = Hessian2Rules::getSymbols();
    }
}