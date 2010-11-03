<?php

require_once('halo_mvc_multiaction_ConfigurationMethodNameResolver.php');
require_once('substrate_stones_IContextStartupAware.php');
require_once('substrate_util_IPathMatcher.php');

class halo_mvc_multiaction_PropertiesMethodNameResolver extends halo_mvc_multiaction_ConfigurationMethodNameResolver {
    
    /**
     * Constructor
     * @param $mappings
     * @param $pathMatcher
     */
    public function __construct(Array $mappings = null, substrate_util_IPathMatcher $pathMatcher = null) {
        parent::__construct(new dd_configuration_MapConfiguration($mappings), $pathMatcher);
    }

    /**
     * Set the configuration instances
     * @param $configuration
     */
    public function setMappings(Array $mappings) {
        $this->setConfiguration(new dd_configuration_MapConfiguration($mappings));
    }
   
}