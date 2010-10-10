<?php

require_once('halo_IHandlerMapping.php');

require_once('substrate_Context.php');
require_once('substrate_stones_IContextAware.php');
require_once('substrate_stones_IOrderedStone.php');

abstract class halo_AbstractHandlerMapping implements halo_IHandlerMapping, substrate_stones_IContextAware, substrate_stones_IOrderedStone {

    /**
     * Substrate context
     * @var substrate_Context
     */
    protected $context;
    
    /**
     * Default handler
     * @var mixed
     */
    protected $defaultHandler;
    
    /**
     * List of interceptors
     * @var Array
     */
    protected $interceptors = array();
    
    /**
     * List of adapted interceptors
     * @var Array
     */
    protected $adaptedInterceptors = array();

    /**
     * Stone order
     * @var number
     */
    protected $stoneOrder = null;

    /**
     * Get a handler
     * @param halo_HttpRequest $httpRequest
     */
    final public function getHandler(halo_HttpRequest $httpRequest) {
        $handler = $this->getHandlerInternal($httpRequest);
        if ( $handler === null ) {
            $handler = $this->defaultHandler;
        }
        if ( $handler === null ) {
            return null;
        }
        if ( is_string($handler) ) {
            //
        }
        return $handler;
    }

    /**
     * We are Substrate context aware
     * @param $context
     */
    public function informAboutContext(substrate_Context $context) {
        $this->context = $context;
        $this->initContext();
    }

    /**
     * Initialize context
     */
    protected function initContext() {
        $this->extendInterceptors($this->interceptors);
        $this->initInterceptors();
    }

    /**
     * Get the adapted interceptors.
     */
    protected function getAdaptedInterceptors() {
        return $this->adaptedInterceptors;
    }

    /**
     * Get the handler execution chain
     * @param mixed $handler
     * @return halo_HandlerExecutionChain
     */
    public function getHandlerExecutionChain(halo_HttpRequest $httpRequest) {
        $handler = $this->getHandler($httpRequest);
        if ( $handler !== null ) {
            // We must have found a handler, wrap it!
            return $this->getHandlerExecutionChainInternal($handler);
        }
        return null;
    }

    /**
     * Get the handler execution chain (internal)
     * @param mixed $handler
     * @return halo_HandlerExecutionChain
     */
    protected function getHandlerExecutionChainInternal($handler) {
        if ( $handler instanceof halo_HandlerExecutionChain ) {
            $handler->addInterceptors($this->getAdaptedInterceptors());
            return $handler;
        } else {
            return new halo_HandlerExecutionChain($handler, $this->getAdaptedInterceptors());
        }
    }

    /**
     * Set interceptors
     * @param $interceptors
     */
    public function setInterceptors($interceptors) {
        if ( ! is_array($interceptors) ) { $interceptors = array($interceptors); }
        foreach ( $interceptors as $interceptor ) {
            $this->interceptors[] = $interceptor;
        }
    }

    /**
     * Hook to allow for additional interceptors to be registred.
     * @param Array
     */
    public function extendInterceptors($interceptors) {
        // noop
    }

    /**
     * Initialize interceptors
     */
    public function initInterceptors() {
        foreach ( $this->interceptors as $interceptor ) {
            if ( is_string($interceptor) ) {
                $interceptor = $this->context->get($interceptor);
            }
            $this->adaptedInterceptors[] = $this->adaptInterceptor($interceptor);
        }
    }

    /**
     * Make certain that this interceptor is something we can use
     * @param $interceptor
     */
    public function adaptInterceptor($interceptor) {
        if ( $interceptor instanceof halo_IHandlerInterceptor ) {
            return $interceptor;
        //} elseif ( $interceptor instanceof halo_ISomethingElse ) {
            //return new halo_HandlerInterceptorInterfaceAdapter($interceptor);
        } else {
            throw new Exception('Interceptor of type "' . get_class($interceptor) . '" not supported.');
        }
    }

    /**
     * Get the order of this stone
     */
    public function getStoneOrder() {
        return $this->stoneOrder;
    }

    /**
     * Set the order of this stone
     * @param $stoneOrder
     */
    public function setStoneOrder($stoneOrder) {
        $this->stoneOrder = $stoneOrder;
    }

    /**
     * Get a handler
     * @param $httpRequest
     */
    abstract protected function getHandlerInternal(halo_HttpRequest $httpRequest);

}