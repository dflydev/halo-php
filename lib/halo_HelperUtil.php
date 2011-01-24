<?php

require_once('halo_IView.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

require_once('halo_DispatcherUtil.php');

require_once('halo_IHelperMappingAware.php');
require_once('halo_IRegisteredHelpersAware.php');

require_once('halo_helper_CompositeRequestHelperMapping.php');

class halo_HelperUtil {
    
    /**
     * Key by which we can set and get our registered helper names for a given request
     * @var string
     */
    protected static $REGISTERED_HELPER_NAMES_KEY = '__halo_registered_helper_names';
    
    protected static $HELPER_MANAGER_KEY = '__halo_HELPER_MANAGER_KEY';
    
    static public function MANAGER(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        
        if ( ! $httpRequest->attributeExists(self::$HELPER_MANAGER_KEY) ) {
        
            $context = halo_DispatcherUtil::GET_CONTEXT($httpRequest);
            
            $httpRequest->setAttribute(
                self::$HELPER_MANAGER_KEY,
                new halo_helper_CompositeRequestHelperMapping(
                    $httpRequest,
                    $httpResponse,
                    $context->findStonesByImplementation('halo_IHelperMapping'),
                    $context->findStonesByImplementation('halo_IHelperFactory'),
                    $context->findStonesByImplementation('halo_IRequestHelperFactory')
                )
            );
            
        }
        
        return $httpRequest->attribute(self::$HELPER_MANAGER_KEY);
        
    }
    
    static public function PREPARE_VIEW(halo_IView $view, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        
        if ( $view instanceof halo_IHelperMappingAware or $view instanceof halo_IRegisteredHelpersAware ) {
            
            $helperMapping = self::MANAGER($httpRequest, $httpResponse);
                        
            if ( $view instanceof halo_IRegisteredHelpersAware ) {
                $view->setRegisteredHelpers(self::GET_REGISTERED_HELPERS($httpRequest, $helperMapping));
            }

            if ( $view instanceof halo_IRegisteredHelperNamesAware ) {
                $view->setRegisteredHelperNames(self::GET_REGISTERED_HELPER_NAMES($httpRequest));
            }
            
            if ( $view instanceof halo_IHelperMappingAware ) {
                $view->setHelperMapping($helperMapping);
            }
            
        }
        
    }
    
    public static function GET_REGISTERED_HELPER_NAMES(halo_HttpRequest $httpRequest) {
        return $httpRequest->attributeExists(self::$REGISTERED_HELPER_NAMES_KEY) ?
            $httpRequest->attribute(self::$REGISTERED_HELPER_NAMES_KEY) : array();
    }
    
    public static function GET_REGISTERED_HELPERS(halo_HttpRequest $httpRequest, halo_IHelperMapping $helperMapping) {
        $helpers = array();
        foreach ( self::GET_REGISTERED_HELPER_NAMES($httpRequest) as $helperName ) {
            $helpers[] = $helperMapping->helper($helperName);
        }
        return $helpers;
    }
        
    public static function REGISTER_HELPER_NAME(halo_HttpRequest $httpRequest, $name) {
        $helperNames = self::GET_REGISTERED_HELPER_NAMES($httpRequest);
        $helperNames[] = $name;
        $httpRequest->setAttribute(self::$REGISTERED_HELPER_NAMES_KEY, $helperNames);
    }
    
    public function HELPER(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $name) {
        self::REGISTER_HELPER_NAME($httpRequest, $name);
        return self::MANAGER($httpRequest, $httpResponse)->helper($name);
    }    
}