<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

interface halo_view_IViewFactory {
    
    /**
     * Does this fatory support the specified view?
     * @param $viewUri
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     * @return bool
     */
    public function supports($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);
    
    /**
     * Build an instance of a view
     * @param $viewName
     * @return halo_view_IView
     */
    public function buildView($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}