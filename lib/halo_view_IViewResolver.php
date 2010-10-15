<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

interface halo_view_IViewResolver {
    
    /**
     * Resolve a view name
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function resolve($viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}