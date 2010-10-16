<?php

require_once('halo_IViewFactory.php');
require_once('halo_view_skittle_SubstrateResourceLocatorAdapter.php');
require_once('halo_view_skittle_SkittleResourceLocatorView.php');

class halo_view_skittle_SkittleViewFactory implements halo_IViewFactory {

    /**
     * Substrate resource locator adapter for Skittle
     * @var halo_view_skittle_SubstrateResourceLocatorAdapter
     */
    protected $substrateResourceLocatorAdapter;
    
    /**
     * Constructor
     * @param $resourceLocator
     */
    public function __construct(substrate_IResourceLocator $resourceLocator) {
        $this->substrateResourceLocatorAdapter = new halo_view_skittle_SubstrateResourceLocatorAdapter($resourceLocator);
    }

    /**
     * (non-PHPdoc)
     * @see halo_IViewFactory::supports()
     */
    public function supports($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        if ( $this->substrateResourceLocatorAdapter->find($viewUri) ) {
            // If our resource locator can find the view URI we know that
            // the view we create will be able to support it.
            return true;
        }
        return false;
    }
    
    /**
     * (non-PHPdoc)
     * @see halo_IViewFactory::buildView()
     */
    public function buildView($viewUri, $viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        return new halo_view_skittle_SkittleResourceLocatorView($viewUri, $this->substrateResourceLocatorAdapter);
    }

}