<?php
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
class halo_DispatcherUtil {
    
    /**
     * Key by which we can set and get our context for a given request
     * @var string
     */
    protected static $CONTEXT_KEY = '__halo_context';
    
    /**
     * Make an HTTP Request.
     * @return halo_HttpRequest
     */
    public static function MAKE_HTTP_REQUEST() {
        return new halo_HttpRequest(
            $_SERVER['REQUEST_METHOD'],
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER
        );
    }
    
    /**
     * Make an HTTP Response.
     * @return halo_HttpResponse
     */
    public static function MAKE_HTTP_RESPONSE() {
        return new halo_HttpResponse();
    }
    
    /**
     * Get the Substrate context from an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @return substrate_Context
     */
    public static function GET_CONTEXT(halo_HttpRequest $httpRequest) {
        return $httpRequest->attribute(self::$CONTEXT_KEY);
    }
    
    /**
     * Set the Substrate context for an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @param substrate_Context $context
     */
    public static function SET_CONTEXT(halo_HttpRequest $httpRequest, substrate_Context $context) {
        return $httpRequest->setAttribute(self::$CONTEXT_KEY, $context);
    }

}