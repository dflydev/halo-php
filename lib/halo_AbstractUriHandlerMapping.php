<?php
require_once('halo_AbstractHandlerMapping.php');
abstract class halo_AbstractUriHandlerMapping extends halo_AbstractHandlerMapping {

    /**
     * Registered handlers
     * @var Array
     */
    protected $registeredHandlers;

    /**
     * (non-PHPdoc)
     * @see halo_AbstractHandlerMapping::initContext()
     */
    protected function initContext() {
        parent::initContext();
        $this->registerHandlers();
    }

    /**
     * Register a handler
     * @param $uriPaths
     * @param $handlerName
     */
    protected function registerHandler($uriPaths, $handlerName) {
        if ( ! is_array($uriPaths) ) $uriPaths = array($uriPaths);
        foreach ( $uriPaths as $uriPath ) {
            $this->registeredHandlers[$uriPath] = $handlerName;
        }
    }

    /**
     * Register handlers
     */
    abstract protected function registerHandlers();

}