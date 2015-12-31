<?php
function loadClass($className) {
        $fileName = '';
        $namespace = '';

        // Sets the include path as the "src" directory
        //$includePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'src';
        $includePath = "/data/www/github.com/hessianphp/src";
	$className = str_replace("HessianPHP","",$className);
        if (false !== ($lastNsPos = strripos($className, '\\'))) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $fullFileName = $includePath . $fileName;
        if (file_exists($fullFileName)) {
            require $fullFileName;
        } else {
            echo 'Class "'.$className.'" does not exist.';
        }
}
spl_autoload_register('loadClass'); // Registers the autoloader
