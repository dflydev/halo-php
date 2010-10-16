<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

require_once('substrate_stones_IOrderedStone.php');

interface halo_IViewResolver extends substrate_stones_IOrderedStone {
    
    /**
     * Resolve a view name
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function resolve($viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}