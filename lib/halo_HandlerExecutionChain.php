<?php

class halo_HandlerExecutionChain {
    
    /**
     * Actual handler
     * @var mixed
     */
    protected $handler;
    
    /**
     * Interceptors
     * @var Array
     */
    protected $interceptors;
    
    /**
     * Constructor
     * @param $handler
     * @param $interceptors
     */
    public function __construct($handler, $interceptors = null) {
        if ( $interceptors == null ) { $interceptors = array(); }
        if ( ! is_array($interceptors) ) { $interceptors = array($interceptors); }
        if ( $handler instanceof halo_HandlerExecutionChain ) {
            $this->handler = $handler->getHandler();
            $this->interceptors = array_merge($handler->getInterceptors(), $interceptors);
        } else {
            $this->handler = $handler;
            $this->interceptors = $interceptors;
        }
    }
    
    /**
     * Get the handler
     */
    public function getHandler() {
        return $this->handler;
    }
    
    /**
     * Get the interceptors
     */
    public function getInterceptors() {
        return $this->interceptors;
    }
    
}