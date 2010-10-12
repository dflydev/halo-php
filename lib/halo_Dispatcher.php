<?php

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

require_once('substrate_stones_IContextStartupAware.php');
require_once('substrate_Context.php');

require_once('substrate_stones_OrderedUtil.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
require_once('halo_ModelAndView.php');

require_once('halo_IView.php');

require_once('halo_HandlerExecutionChain.php');

class halo_Dispatcher implements substrate_stones_IContextStartupAware {
    
    /**
     * @var dd_logging_ILogger
     */
    static public $LOGGER;
    
    /**
     * We will be told about our context
     * @var substrate_Context
     */
    protected $context;
    
    /**
     * Called once the context has started
     * @param $context
     */
    public function informAboutContextStartup(substrate_Context $context) {
        
        // Remember this.
        $this->context = $context;

        // Find all handler mappings.
        $this->handlerMappings = substrate_stones_OrderedUtil::SORT(
            $context->findStonesByImplementation('halo_IHandlerMapping')
        );
        
        // Find all handler adapters.
        $this->handlerAdapters = substrate_stones_OrderedUtil::SORT(
            $context->findStonesByImplementation('halo_IHandlerAdapter')
        );

        // Find all view resolvers.
        $this->viewResolvers = $context->findStonesByImplementation(
            'halo_IViewResolver'
        );

    }
    
    /**
     * Catch fatal errors
     * @param $buffer
     * @return buffer
     */
    public function doServiceErrorHandler($buffer) {
        $error = error_get_last();
        if ( $error !== null and $error['type'] == 1 ) {
            if ( self::$LOGGER->isFatalEnabled() ) {
                self::$LOGGER->fatal('SOMETHING MAJOR HAPPENED');
                self::$LOGGER->fatal($error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']);
            }
        }
        return $buffer;
    }
    
    /**
     * Handle internal server error
     */
    protected function handleInternalServerError() {
        // TODO: Can we find a way to customize 500 error?
        header('HTTP/1.1 500 Internal Server Error');
        echo '<strong>500 Internal Server Error</strong><br />';
        echo 'An internal error has occurred.';
    }

    /**
     * Main entry point.
     * @param halo_HttpRequest $httpRequest
     * @param halo_HttpResponse $httpResponse
     */
    public function doService(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        
        $didCatchException = false;
        $exception = null;
        
        ob_start(array($this, 'doServiceErrorHandler'));;
        
        try {
            $this->doServiceInternal($httpRequest, $httpResponse);
        } catch (Exception $e) {
            $didCatchException = true;
            $exception = $e;
        }
        
        if ( $didCatchException ) {
            if ( self::$LOGGER->isErrorEnabled() ) {
                self::$LOGGER->error('Something pretty bad happened.');
                self::$LOGGER->error('Caught exception: ' . get_class($exception) . ' [code:' . $exception->getCode() . ']');
                self::$LOGGER->error($exception->getMessage());
            }
            while (ob_get_level()) {
                ob_end_clean();
            }
            $this->handleInternalServerError();
        } else {
            // Everything is all good here.
            ob_flush();
        }
    }
    
    /**
     * This is a safe internal method for handling the service
     * @param halo_HttpRequest $httpRequest
     * @param halo_HttpResponse $httpResponse
     */
    protected function doServiceInternal(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        
        // Let's get this party started!
        halo_DispatcherUtil::SET_CONTEXT($httpRequest, $this->context);

        $handlerExecutionChain = $this->getHandlerExecutionChain($httpRequest);
        
        if ( $handlerExecutionChain === null or $handlerExecutionChain->getHandler() == null ) {
            throw new Exception('Unable to find a handler for request.');
        }
        
        $handler = $handlerExecutionChain->getHandler();
        $interceptorIdx = -1;
        foreach ( $handlerExecutionChain->getInterceptors() as $interceptor ) {
            $interceptorIdx++;
            if ( ! $interceptor->preHandle($httpRequest, $httpResponse, $handler) ) {
                $this->triggerAfterCompletion($handlerExecutionChain, $interceptorIdx, $httpRequest, $httpResponse);
                return;
            }
        }

        $handlerAdapter = $this->getHandlerAdapter($handler);

        if ( $handlerAdapter === null ) {
            throw new Exception('Unable to find a handler adapter for handler.');
        }

        $modelAndView = $handlerAdapter->handle($httpRequest, $httpResponse, $handler);

        foreach ( $handlerExecutionChain->getInterceptors() as $interceptor ) {
            $interceptor->postHandle($httpRequest, $httpResponse, $handler, $modelAndView);
        }

        $this->render($modelAndView, $httpRequest, $httpResponse);

        $this->triggerAfterCompletion($handlerExecutionChain, $interceptorIdx, $httpRequest, $httpResponse);        
        
    }
    
    /**
     * Get handler execution change for the request
     * 
     * Checks all handler mappings to see if at least one can handle the
     * request (likely by way of examining the request URI).
     * 
     * @param halo_HttpRequest $httpRequest
     * @return halo_HandlerExecutionChain
     */
    public function getHandlerExecutionChain(halo_HttpRequest $httpRequest) {
        foreach ( $this->handlerMappings as $handlerMapping ) {
            $handler = $handlerMapping->getHandlerExecutionChain($httpRequest);
            if ( $handler !== null ) {
                return $handler;
            }
        }
        return null;
    }
    
    /**
     * Find something that can handle this handler
     * @param $handler
     */
    public function getHandlerAdapter($handler) {
        foreach ( $this->handlerAdapters as $handlerAdapter ) {
            if ( $handlerAdapter->supports($handler) ) {
                return $handlerAdapter;
            }
        }
        return null;
    }
    
    /**
     * Render
     * @param halo_ModelAndView $modelAndView
     * @param halo_HttpRequest $httpRequest
     * @param halo_HttpResponse $httpResponse
     */
    public function render(halo_ModelAndView $modelAndView = null, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        if ( $modelAndView === null ) return;
        $view = null;
        if ( $modelAndView->isReferenced() ) {
            $view = $this->resolveViewName($modelAndView->getViewName(), $httpRequest, $httpResponse);
        } else {
            $view = $modelAndView->getView();
        }
        if ($view and is_object($view) and $view instanceof halo_IView ) {
            $viewContent = $view->render($modelAndView->getModel(), $httpRequest, $httpResponse);
            print $viewContent;
        }
    }
    
    /**
     * Resolve a view name
     * @param $viewName
     * @param $httpRequest
     * @param $httpResponse
     */
    public function resolveViewName($viewName, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        foreach ( $this->viewResolvers as $viewResolver ) {
            $view = $viewResolver->resolve($viewName, $httpRequest, $httpResponse);
            if ( $view !== null ) return $view;
        }
        return $viewName;
    }
    
    /**
     * Trigger after completion (FINALLY)
     * @param $handlerExecutionChain
     * @param $interceptorIdx
     * @param $httpRequest
     * @param $httpResponse
     * @param $exception
     */
    public function triggerAfterCompletion(halo_HandlerExecutionChain $handlerExecutionChain, $interceptorIdx, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $exception = null) {
        if ( $handlerExecutionChain !== null ) {
            $interceptors = $handlerExecutionChain->getInterceptors();
            for ( $i = $interceptorIdx; $i >= 0; $i-- ) {
                $interceptor = $interceptors[$i];
                try {
                    $interceptor->afterCompletion($httpRequest, $httpResponse, $handlerExecutionChain->getHandler(), $exception);
                } catch (Exception $e) {
                    $this->logger->info('IHandlerInterceptor.afterCompletion() threw exception.');
                }
            }
        }
    }

}

halo_Dispatcher::$LOGGER = dd_logging_LogFactory::get('halo_Dispatcher');
