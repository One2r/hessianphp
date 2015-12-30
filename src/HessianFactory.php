<?php
/*
 * This file is part of the HessianPHP package.
 * (c) 2004-2010 Manuel G髆ez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HessianPHP;
use HessianPHP\Hessian2\Hessian2ServiceParser;
use HessianPHP\Hessian2\Hessian2IteratorWriter;
use HessianPHP\Hessian2\Hessian2ServiceWriter;
use HessianPHP\Interfaces\IHessianObjectFactory;

define('HESSIAN_PHP_VERSION', '2.0');

/**
 * Handles que creation of components for assembling Hessian clients and servers
 * It contains the basic assembly configuration for these components.
 * @author vsayajin
 *
 */
class HessianFactory{
	var $protocols = array();
	var $transports = array();
	static $cacheRules = array();
	
	function __construct(){
		$this->protocols = array(
			'2'=>array($this,'loadVersion2')
		);
		$this->transports = array(
			'CURL' => 'HessianPHP\Transport\HessianCURLTransport',
			'curl' => 'HessianPHP\Transport\HessianCURLTransport',
			'http' => 'HessianPHP\Transport\HessianHttpStreamTransport'
		);
	}
	
	/**
	 * Returns a specialized HessianParser object based on the options object
	 * @param HessianStream $stream input stream
	 * @param HessianOptions $options configuration options
	 */
	function getParser($stream, $options){
		$version = $options->version;
		if($options->detectVersion && $stream)
			$version = $this->detectVersion($stream, $options);
		$callback = $this->getConfig($version);
		$parser = call_user_func_array($callback, array('parser', $stream, $options));
		if($options->objectFactory instanceof IHessianObjectFactory){
			$parser->objectFactory = $options->objectFactory;
		} else {
			$parser->objectFactory = new HessianObjectFactory();
		}
		return $parser;
	}
	
	/**
	 * Returns a specialized HessianWriter object based on the options object
	 * @param HessianStream $stream output stream
	 * @param HessianOptions $options configuration options
	 */
	function getWriter($stream, $options){
		$version = $options->version;
		if($options->detectVersion && $stream)
			$version = $this->detectVersion($stream, $options);
		$callback = $this->getConfig($version);
		$writer = call_user_func_array($callback, array('writer', $stream, $options));
		return $writer;
	}
	
	/**
	 * Creates a parsing helper object (rules resolver) that uses a protocol
	 * rule file to parse the incomin stream. It caches the rules for further
	 * use.
	 * @param Integer $version Protocol version
	 * @param array $config local component configuration
	 */
	public function getRulesResolver($version, $rulesPath=''){
		if(isset(self::$cacheRules[$version]))
			return self::$cacheRules[$version];
		$resolver = new HessianRuleResolver($rulesPath);
		self::$cacheRules[$version] = $resolver;
		return $resolver;
	}
	
	/**
	 * Receives a stream and iterates over que registered protocol handlers
	 * in order to detect which version of Hessian is it
	 * @param HessianStream $stream
	 * @return integer Protocol version detected
	 */
	function detectVersion($stream, $options){
		foreach($this->protocols as $version => $callback){
			$res = call_user_func_array($callback, array('detect', $stream, $options));
			if($res)
				return $version;		
		}
		throw new \Exception("Cannot detect protocol version on stream");
	}
	
	function getConfig($version){
		if(!isset($this->protocols[$version]))
			throw new \Exception("No configuration for version $version protocol");
		return $this->protocols[$version];
	}
	
	function getTransport(HessianOptions $options){
		$type = $options->transport;
		if(is_object($type))
			return $type;
		if(!isset($this->transports[$type]))
			throw new HessianException("The transport of type $type cannot be found");
		$class = $this->transports[$type];
		$trans = new $class();
		$trans->testAvailable();
		return $trans; 
	}
	
	function loadVersion2($mode, $stream, $options){
		if($mode == 'parser'){
			$resolver = $this->getRulesResolver(2, 'Hessian2/hessian2rules.php');
			$parser = new Hessian2ServiceParser($resolver, $stream, $options);
			$filters['date'] = array('\HessianPHP\HessianDatetimeAdapter','toObject');
			$filters = array_merge($filters, $options->parseFilters);
			$parser->setFilters(new HessianCallbackHandler($filters));
			return $parser;
		}
		if($mode == 'writer'){
			$writer = new Hessian2ServiceWriter($options);
			$filters['@DateTime'] = array('\HessianPHP\HessianDatetimeAdapter','writeTime');
			$filters['@Iterator'] = array( new Hessian2IteratorWriter(), 'write');
			$filters = array_merge($filters, $options->writeFilters);
			$writer->setFilters(new HessianCallbackHandler($filters));
			return $writer;
		}
		if($mode == 'detect'){
			return Hessian2ServiceParser::detectVersion($stream);
		}
	}
}



