<?php

require_once('skittle_Skittle.php');

require_once('halo_view_AbstractUriView.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_view_skittle_SkittleResourceLocatorView extends halo_view_AbstractUriView {
    
    /**
     * Skittle resource locator
     * @var skittle_IResourceLocator
     */
    protected $resourceLocator;
    
    /**
     * Constructor
     * @param $uri
     * @param $resourceLocator
     */
    public function __construct($uri, skittle_IResourceLocator $resourceLocator) {
        parent::__construct($uri);
        $this->resourceLocator = $resourceLocator;
    }
    
    /**
     * (non-PHPdoc)
     * @see halo_view_IView::render()
     */
    public function render(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $skittle = new skittle_Skittle($this->resourceLocator);
        return $skittle->stringInc($this->uri, $model);
    }
    
}