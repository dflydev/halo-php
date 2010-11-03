<?php

require_once('halo_HttpRequest.php');

interface halo_mvc_multiaction_IMethodNameResolver {

    /**
     * Get the method name for the request
     * @param $request
     */
    public function getHandlerMethodName(halo_HttpRequest $request);

}