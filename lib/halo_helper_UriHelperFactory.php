<?php

require_once('halo_IRequestHelperFactory.php');
require_once('dd_configuration_IConfiguration.php');
require_once('dd_uri_Uri.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_helper_UriHelperFactory implements halo_IRequestHelperFactory {

    /**
     * Configuration
     * @var dd_configuration_IConfiguration
     */
    protected $configuration;
    
    /**
     * Prefix
     * @var string
     */
    protected $prefix;
    
    /**
     * Base suffix
     * @var string
     */
    protected $baseSuffix;
    
    /**
     * Secure base suffix
     * @var string
     */
    protected $secureBaseSuffix;
    
    /**
     * Constructor
     * @param $configuration
     */
    public function __construct(dd_configuration_IConfiguration $configuration = null) {
        $this->configuration = $configuration;
    }
    
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }
    
    public function setBaseSuffix($baseSuffix) {
        $this->baseSuffix = $baseSuffix;
    }
    
    public function setSecureBaseSuffix($secureBaseSuffix) {
        $this->secureBaseSuffix = $secureBaseSuffix;
    }
    
    public function supports($name) {
        return $name == 'uri';
    }
    
    public function helper($name, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $uri = new dd_uri_Uri(
            $this->configuration,
            $httpRequest->scriptPathRoot(),
            false,
            $httpRequest->envExport()
        );
        if ( $this->prefix ) $uri->setPrefix($this->prefix);
        if ( $this->baseSuffix ) $uri->setBaseSuffix($this->baseSuffix);
        if ( $this->secureBaseSuffix ) $uri->setSecureBaseSuffix($this->secureBaseSuffix);
        return $uri;
    }
    
}