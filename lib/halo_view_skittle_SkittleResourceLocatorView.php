<?php

require_once('skittle_Skittle.php');
require_once('halo_view_skittle_AbstractSkittleView.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_view_skittle_SkittleResourceLocatorView extends halo_view_skittle_AbstractSkittleView {
    
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
     * Instantiate Skittle.
     * @param $model
     * @param $httpRequest
     * @param $httpResponse
     */
    public function instantiate(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        return new skittle_Skittle($this->resourceLocator);
    }
    
}