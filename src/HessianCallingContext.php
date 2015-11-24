<?php
/*
 * This file is part of the HessianPHP package.
 * (c) 2004-2010 Manuel G髆ez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HessianPHP;

class HessianCallingContext{
	public $writer;
	public $parser;
	public $transport;
	public $typemap;
	public $options;
	public $stream;
	public $isClient = true;
	public $call;
	public $url;
	public $payload;
	public $error;
}