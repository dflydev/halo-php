<?php

require_once('halo_handler_AbstractUriHandlerMapping.php');
require_once('halo_HttpRequest.php');

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

require_once('substrate_util_IPathMatcher.php');

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
     * Path matcher
     * @var substrate_util_IPathMatcher
     */
    protected $pathMatcher;
    
    /**
     * Constructor
     * @param $mappings
     * @param $default
     */
    public function __construct(array $mappings, $default = null, substrate_util_IPathMatcher $pathMatcher = null) {
        $this->mappings = $mappings;
        $this->default = $default;
        if ( $default !== null ) {
            $this->useDefault = true;
        } else {
            $this->useDefault = false;
        }
        $this->pathMatcher = $pathMatcher;
    }
    
    /**
     * Get the actual handler
     * @param $httpRequest
     */
    protected function getHandlerInternal(halo_HttpRequest $httpRequest) {
        $uri = $httpRequest->requestedUri();
        if ($this->useDefault and ($uri === null or $uri === '' or $uri === '/') and $this->default !== null) {
            return $this->context->get($this->default);
        }
        if ( isset($this->registeredHandlers[$uri]) ) {
            return $this->context->get($this->registeredHandlers[$uri]);
        }
        foreach ($this->registeredHandlers as $key => $value) {
            if ($this->pathMatcher->match($key, $uri)) {
                return $this->context->get($value);
            }
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

    /**
     * Set the path matcher
     * @param substrate_util_IPathMatcher $pathMatcher
     */
    public function setPathMatcher(substrate_util_IPathMatcher $pathMatcher) {
        $this->pathMatcher = $pathMatcher;
    }
    
}

halo_handler_SimpleUriHandlerMapping::$LOGGER = dd_logging_LogFactory::get('halo_handler_SimpleUriHandlerMapping');
