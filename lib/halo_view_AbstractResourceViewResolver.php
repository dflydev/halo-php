<?php

require_once('halo_view_AbstractViewResolver.php');

abstract class halo_view_AbstractResourceViewResolver extends halo_view_AbstractViewResolver {


    /**
     * Prefix on URI
     * @var string
     */
    protected $prefix;
    
    /**
     * Suffix on URI
     * @var string
     */
    protected $suffix;

    /**
     * (non-PHPdoc)
     * @see halo_IViewResolver::resolve()
     */
    final public function resolve($viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $viewUri = $this->prefix . $viewName . $this->suffix;
        return $this->doResolveViewUri($viewUri, $viewName, $httpRequest, $httpResponse);
    }
    
    /**
     * Continue resolving the view URI
     * 
     * Expected to return instance of halo_view_IView or null if not accepted.
     * @param string $viewUri
     * @param string $viewName
     * @param halo_HttpRequest $httpRequest
     * @param halo_HttpResponse $httpResponse
     */
    abstract public function doResolveViewUri($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);
    
    /**
     * Set prefix
     * @param $preffix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }
    
    /**
     * Set suffix
     * @param $suffix
     */
    public function setSuffix($suffix) {
        $this->suffix = $suffix;
    }

}
