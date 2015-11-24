<?php
/*
 * This file is part of the HessianPHP package.
 * (c) 2004-2010 Manuel G髆ez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HessianPHP;
/**
 * Used by custom write filters to return a stream instead of a modified object 
 * @author vsayajin
 */
class HessianStreamResult {
	var $stream;
	function __construct($stream){
		$this->stream = $stream;
	}
}