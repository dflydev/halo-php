<?php

require_once('halo_view_AbstractResourceViewResolver.php');
require_once('halo_IViewFactory.php');
require_once('substrate_IClassLoader.php');

class halo_view_ViewFactoryResourceViewResolver extends halo_view_AbstractResourceViewResolver {
    
    /**
     * View factory
     * @var halo_IViewFactory
     */
    protected $viewFactory;
    
    /**
     * Constructor
     * @param $viewFactory
     * @param $classLoader
     */
    public function __construct(halo_IViewFactory $viewFactory, substrate_IClassLoader $classLoader = null) {
        parent::__construct($classLoader);
        $this->viewFactory = $viewFactory;
    }
    
    /**
     * Resolve the view (conditionally)
     * 
     * Checks the underlying halo_IViewFactory by way of supports() to see
     * if the view built by the factory will support the view request. If it
     * does support it, the factory will build a view. If not, null is returned.
     * 
     * This view resolver is safe to use if view resolver chaining is desired
     * since it may return null in some cases.
     * 
     * @param $viewUri
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function doResolveViewUri($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        if ( $this->viewFactory->supports($viewUri, $viewName, $httpRequest, $httpResponse) ) {
            return $this->viewFactory->buildView($viewUri, $viewName, $httpRequest, $httpResponse);
        }
        return null;
    }
    
}