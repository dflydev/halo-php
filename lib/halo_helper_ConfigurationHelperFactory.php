<?php

require_once('halo_IRequestHelperFactory.php');
require_once('dd_configuration_IConfiguration.php');
require_once('halo_helper_ConfigurationHelper.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_helper_ConfigurationHelperFactory implements halo_IRequestHelperFactory {

    /**
     * Configuration
     * @var dd_configuration_IConfiguration
     */
    protected $configuration;
    
    /**
     * Constructor
     * @param $uriConfiguration
     */
    public function __construct(dd_configuration_IConfiguration $configuration) {
        $this->configuration = $configuration;
    }
    
    public function supports($name) {
        return $name == 'configuration';
    }
    
    public function helper($name, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        return new halo_helper_ConfigurationHelper($this->configuration);
    }
    
}
