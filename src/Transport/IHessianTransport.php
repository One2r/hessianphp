<?php
namespace HessianPHP\Transport;
/**
 * RContract for a network request to remote services
 */
interface IHessianTransport {
    /**
     * Executes a POST request to a remote Hessian service and returns a
     * HessianStream for reading data
     * @param string $url url of the remote service
     * @param binary $data binary data payload
     * @param HessianOptions $options optional parameters for the transport
     * @return HessianStream input stream
     */
    function getStream($url, $data, $options);
    /**
     * Tests wether the transport is available in this installation
     */
    function testAvailable();
    function getMetadata();
}