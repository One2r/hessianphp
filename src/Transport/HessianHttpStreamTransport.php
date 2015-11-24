<?php
namespace HessianPHP\Transport;
use HessianPHP\HessianStream;
/**
 * Hessian request using PHP's http streaming context
 */
class HessianHttpStreamTransport implements IHessianTransport{
    var $metadata;
    var $options;
    var $rawData;
    var $lastError = '';

    function testAvailable(){
        if(!ini_get('allow_url_fopen'))
            throw new \Exception("You need to enable allow_url_fopen to use the stream transport");
    }

    function getMetadata(){
        return $this->metadata;
    }

    function getStream($url, $data, $options){
        $this->lastError = '';
        $bytes = str_split($data);

        $params = array(
            'method'=>"POST",
            'header'=> array("Content-Type: application/binary",
                "Content-Length: ".count($bytes) ),
            'timeout' => 3,
            'content' => $data
        );

        if(!empty($options->transportOptions)){
            $http = $options->transportOptions;
            if(isset($http['header'])){
                $headers = $http['header'];
                $newheaders = null;
                if(is_string($headers)){
                    $newheaders = explode("\n", $headers);
                }
                if(is_array($headers))
                    $newheaders = $headers;
                if(!empty($newheaders)) {
                    //$params['header'] = array();
                    foreach($newheaders as $header){
                        $params['header'][] = trim($header);
                    }
                }
            }
            $params = $params + $http;
            if(isset($http['timeout']))
                $params['timeout'] = $http['timeout'];
        }

        $scheme = 'http';
        if(strpos($url, 'https') === 0)
            $scheme = 'https';
        $opt = array($scheme => $params);
        set_error_handler(array($this, 'httpErrorHandler'));
        $ctx = stream_context_create($opt);
        $fp = fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            restore_error_handler();
            throw new \Exception("Conection problem, message: $this->lastError");
        }
        $response = stream_get_contents($fp);
        if ($response === false) {
            if($fp)
                fclose($fp);
            restore_error_handler();
            throw new \Exception("Problem reading data from url, message: $this->lastError");
        }
        restore_error_handler();
        $this->metadata = stream_get_meta_data($fp);
        $this->metadata['http_headers'] = array();
        foreach ($this->metadata['wrapper_data'] as $raw_header) {
            $parts = explode(':', $raw_header);
            $header = $parts[0];
            $data = count($parts) > 1 ? $parts[1] : '';
            $this->metadata['http_headers'][strtolower($header)] = trim($data);
        }
        fclose($fp);
        if(!empty($options->saveRaw))
            $this->rawData = $response;
        $stream = new HessianStream($response, $this->metadata['http_headers']['content-length']);
        return $stream;
    }

    function httpErrorHandler($errno, $errstr, $errfile, $errline){
        $this->lastError = $errstr;
        return true;
    }
}