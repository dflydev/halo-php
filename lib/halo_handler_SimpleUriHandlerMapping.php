<?php

require_once('halo_handler_AbstractUriHandlerMapping.php');
require_once('halo_HttpRequest.php');

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

class halo_handler_SimpleUriHandlerMapping extends halo_handler_AbstractUriHandlerMapping {
    
    /**
     * Logger
     * @var dd_logging_ILogger
     */
    public static $LOGGER;

    /**
     * URI to handler mappings
     * @var Array
     */
    protected $mappings;
    
    /**
     * Should we use a default handler?
     * @var Bool
     */
    protected $useDefault;
    
    /**
     * Constructor
     * @param $mappings
     * @param $default
     */
    public function __construct(array $mappings, $default = null) {
        $this->mappings = $mappings;
        $this->default = $default;
        if ( $default !== null ) {
            $this->useDefault = true;
        } else {
            $this->useDefault = false;
        }
    }
    
    /**
     * Get the actual handler
     * @param $httpRequest
     */
    protected function getHandlerInternal(halo_HttpRequest $httpRequest) {
        $uri = $httpRequest->requestedUri();
        if ( $this->useDefault and ( $uri === null or $uri === '' ) and $this->default !== null ) {
            return $this->context->get($this->default);
        }
        if ( isset($this->registeredHandlers[$uri]) ) {
            return $this->context->get($this->registeredHandlers[$uri]);
        }
        if ( self::$LOGGER->isInfoEnabled() ) {
            self::$LOGGER->info('Unable to handle request for URI: [' . $uri . ']');
        }
        return null;
    }

    /**
     * Register the handlers
     */
    protected function registerHandlers() {
        foreach ( $this->mappings as $uri => $objectName ) {
            $this->registerHandler($uri, $objectName);
        }
    }
    
}

halo_handler_SimpleUriHandlerMapping::$LOGGER = dd_logging_LogFactory::get('halo_handler_SimpleUriHandlerMapping');
