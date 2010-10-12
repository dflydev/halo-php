<?php

require_once('halo_AbstractViewResolver.php');

require_once('substrate_IResourceLocator.php');
require_once('substrate_IClassLoader.php');

class halo_ResourceLocatorViewResolver extends halo_AbstractViewResolver {
    
    /**
     * Resource locator (for view files)
     * @var substrate_IResourceLocator
     */
    protected $resourceLocator;
    
    protected $viewClass;
    
    protected $preffix = '';
    
    protected $suffix = '';
    
    /**
     * Constructor
     * @param $resourceLocator
     * @param $classLoader
     */
    public function __construct(substrate_IResourceLocator $resourceLocator = null, substrate_IClassLoader $classLoader = null) {
        parent::__construct($classLoader);
        if ( $resourceLocator === null ) {
            require_once('substrate_ClasspathResourceLocator.php');
            $this->resourceLocator = new substrate_ClasspathResourceLocator();
        } else {
            $this->resourceLocator = $resourceLocator;
        }
    }
    
    /**
     * Resolve a view name
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function resolve($viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $completeViewUri = $this->preffix.$viewName.$this->suffix;
        $viewUri = $this->resourceLocator->find($completeViewUri);
        if ( ! $viewUri ) { return null; }
        $this->classLoader->load($this->viewClass);
        $viewClass = $this->viewClass;
        return new $viewClass($viewName, $viewUri);
    }

    /**
     * Set the view class to use
     * @param $viewClass
     */
    public function setViewClass($viewClass) {
        $this->viewClass = $viewClass;
    }
    
    /**
     * Set a prefix to apply to a view name
     * @param $preffix
     */
    public function setPreffix($preffix) {
        $this->preffix = $preffix;
    }
    
    /**
     * Set a suffix to apply to a view name
     * @param $suffix
     */
    public function setSuffix($suffix) {
        $this->suffix = $suffix;
    }
            
}
