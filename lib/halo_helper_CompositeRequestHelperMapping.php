<?php

require_once('halo_IHelperMapping.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_helper_CompositeRequestHelperMapping implements halo_IHelperMapping {

    protected $found = array();
    protected $instance = array();
    
    public function __construct(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $mappings = null, $factories = nul, $requestFactories = null) {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
        $this->mappings = $mappings;
        $this->factories = $factories;
        $this->requestFactories = $requestFactories;
    }

    public function supports($name) {

        if ( array_key_exists($name, $this->found) ) {
            return is_array($this->found[$name]) ? true : false;
        }

        // First check the mappings.
        foreach ( $this->mappings as $mappingIdx => $mapping ) {
            if ( $mapping->supports($name) ) {
                $this->found[$name] = array('mapping', $mappingIdx);
                return true;
            }
        }
        
        // Next check the factories.
        foreach ( $this->factories as $factoriesIdx => $factory ) {
            if ( $factory->supports($name) ) {
                $this->found[$name] = array('factory', $factoriesIdx);
                return true;
            }
        }
        
        // Next check the request factories.
        foreach ( $this->requestFactories as $requestFactoriesIdx => $requestFactory ) {
            if ( $requestFactory->supports($name) ) {
                $this->found[$name] = array('requestFactory', $requestFactoriesIdx);
                return true;
            }
        }
        
        return false;
    }

    public function helper($name) {
        
        if ( isset($this->instance[$name]) ) {
            return $this->instance[$name];
        }
        
        $value = $this->internalHelper($name);
        
        if ( $value !== null ) {
            $this->instance[$name] = $value;
        }

        return $value;
        
    }

    public function internalHelper($name) {
        
        if ( ! $this->supports($name) ) { return null; }
        
        list($type, $idx) = $this->found[$name];
        
        switch($type) {
            
            case 'mapping':
                $mapping = $this->mappings[$idx];
                $helper = $mapping->helper($name);
                if ( $helper instanceof halo_helper_IRequestHelperFactory ) {
                    // The mapper passed back a request helper factory.
                    return $helper->helper($name, $this->httpRequest, $this->httpResponse);
                }
                if ( $helper instanceof halo_helper_IHelperFactory ) {
                    // The mapper passed back a helper factory.
                    return $helper->helper($name);
                }
                return $helper;
                break;

            case 'factory':
                $factory = $this->factories[$idx];
                return $factory->helper($name);
                break;

            case 'requestFactory':
                $requestFactory = $this->requestFactories[$idx];
                return $requestFactory->helper($name, $this->httpRequest, $this->httpResponse);
                break;
                
            default:
                return null;
                break;

        }

    }

}