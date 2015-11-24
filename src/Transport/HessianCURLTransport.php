<?php
namespace HessianPHP\Transport;
use HessianPHP\HessianStream;
/**
 * Hessian request using the CURL library
 */
class HessianCURLTransport implements IHessianTransport{
    var $metadata;
    var $rawData;

    function testAvailable(){
        if(!function_exists('curl_init'))
            throw new \Exception('You need to enable the CURL extension to use the curl transport');
    }

    function getMetadata(){
        return $this->metadata;
    }

    function getStream($url, $data, $options){
        $ch = curl_init($url);
        if(!$ch)
            throw new \Exception("curl_init error for url $url.");

        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array("Content-Type: application/binary")
        );

        if(!empty($options->transportOptions)){
            $extra = $options->transportOptions;
            if(isset($extra[CURLOPT_HTTPHEADER])){
                $curlOptions[CURLOPT_HTTPHEADER] = array_merge($curlOptions[CURLOPT_HTTPHEADER]
                    , $extra[CURLOPT_HTTPHEADER]);
            }
            // array combine operation, does not overwrite existing keys
            $curlOptions = $curlOptions + $options->transportOptions;
        }
        curl_setopt_array($ch, $curlOptions);

        $result = curl_exec($ch);
        $this->metadata = curl_getinfo($ch);
        $error = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($error){
            $this->safeClose($ch);
            throw new \Exception("CURL transport error: $error");
        }
        if($result === false) {
            $this->safeClose($ch);
            throw new \Exception("curl_exec error for url $url");
        }
        if(!empty($options->saveRaw))
            $this->rawData = $result;
        $this->safeClose($ch);
        if($code != 200){
            $this->safeClose($ch);
            $msg = "Server error, returned HTTP code: $code";
            if(!empty($options->saveRaw))
                $msg .= " Server sent: ".$result;
            throw new \Exception($msg);
        }
        $stream = new HessianStream($result);
        return $stream;
    }

    private function safeClose($ch){
        if(is_resource($ch))
            curl_close($ch);
    }
}