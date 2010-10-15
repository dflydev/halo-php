<?php

require_once('halo_view_AbstractResourceViewResolver.php');

class halo_view_InternalResourceViewResolver extends halo_view_AbstractResourceViewResolver {
    
    /**
     * Creates a new instance of viewClass instantiated with the viewUri
     * 
     * In thise case, we accept any and all specified URI. No other view
     * resolvers will be checked!
     * 
     * @param $viewUri
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function doResolveViewUri($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $this->classLoader->load($this->viewClass);
        return new $this->viewClass($viewUri);
    }
    
}
