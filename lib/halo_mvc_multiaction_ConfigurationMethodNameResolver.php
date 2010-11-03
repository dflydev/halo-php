<?php

require_once('halo_mvc_multiaction_IMethodNameResolver.php');
require_once('substrate_stones_IContextStartupAware.php');
require_once('substrate_util_IPathMatcher.php');

class halo_mvc_multiaction_ConfigurationMethodNameResolver implements halo_mvc_multiaction_IMethodNameResolver, substrate_stones_IContextStartupAware {

    /**
     * Configuration
     * @var dd_configuration_IConfiguration
     */
    private $configuration;

    /**
     * 
     * Enter description here ...
     * @var substrate_util_IPathMatcher
     */
    private $pathMatcher;

    /**
     * Constructor
     * @param $configuration
     * @param $pathMatcher
     */
    public function __construct(dd_configuration_IConfiguration $configuration = null, substrate_util_IPathMatcher $pathMatcher = null) {
        $this->configuration = $configuration;
        $this->pathMatcher = $pathMatcher;
    }
    
    /**
     * Informed that the context has started
     * @param $context
     */
    public function informAboutContextStartup(substrate_Context $context) {
        if ( $this->pathMatcher === null ) {
            require_once('substrate_util_AntPathMatcher.php');
            $this->pathMatcher = new substrate_util_AntPathMatcher();
        }
    }

    /**
     * Set the configuration instances
     * @param $configuration
     */
    public function setConfiguration(dd_configuration_IConfiguration $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * Set the PathMatcher implementation to use for matching URL paths
     * against registered URL patterns. Default is halo_AntPathMatcher.
     * @see halo_AntPathMatcher
     */
    public function setPathMatcher(halo_IPathMatcher $pathMatcher) {
        $this->pathMatcher = $pathMatcher;
    }

    /**
     * (non-PHPdoc)
     * @see halo_mvc_multiaction_IMethodNameResolver::getHandlerMethodName()
     */
    public function getHandlerMethodName(halo_HttpRequest $request){
        $uriPath = $request->getRequestedUri();
        if ( $this->configuration->exists($uriPath) ) {
            return $this->configuration->get($uriPath);
        }

        foreach ($this->configuration->keys() as $key ) {
            if ($this->pathMatcher->match($key, $uriPath)) {
                return $this->configuration->get($key);
            }
        }
    }
    
}