<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

interface halo_IHandlerAdapter {
    
    /**
     * Determine whether this handler adapter supports a given handler
     * @param $object
     */
    public function supports($object);
    
    /**
     * Handle a request
     * @param $httpRequest
     * @param $httpResponse
     * @param $handler
     */
    public function handle(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler);
    
}
