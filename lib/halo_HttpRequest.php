<?php

require_once('dd_logging_ILogger.php');
require_once('dd_logging_LogFactory.php');

class halo_HttpRequest {
    
    /**
     * Logger
     * @var dd_logging_ILogger
     */
    static public $LOGGER;

    /**
     * Request method.
     * @var string
     */
    protected $method;

    /**
     * Query params.
     * 
     * Think $_GET.
     * @var array
     */
    protected $queryParams;

    /**
     * Post params.
     * 
     * Think $_POST.
     * @var array
     */
    protected $postParams;

    /**
     * File data
     * 
     * Think $_FILES.
     * @var array
     */
    protected $fileData;

    /**
     * Environment
     * 
     * Think $_ENV.
     * @var Array
     */
    protected $env;

    /**
     * Attributes
     * 
     * Customized attributes added by the application. Can be used to
     * add additional context to the request but are not required.
     * @var Array
     */
    protected $attributes = array();

    /**
     * URI Params.
     * 
     * Experimental. The URI may be parsed for params depending on the
     * handler mapping.
     * @var array
     */
    protected $uriParams = array();

    /**
     * Script path root.
     * @var string
     */
    protected $scriptPathRoot;

    /**
     * Original requested URI.
     * @var string
     */
    protected $requestedUri;
    
    /**
     * Body of request
     * @var mixed
     */
    protected $body;

    /**
     * Constructor
     * @param $method
     * @param $queryParams
     * @param $postParams
     * @param $fileData
     * @param $env
     * @param $body
     */
    public function __construct($method, array $queryParams = null, array $postParams = null, array $fileData = null, array $env = null, $body = null) {
        
        if ( $env === null ) $env = array();
        
        foreach ( array('PATH_INFO', 'SCRIPT_PATH_INFO') as $envKey ) {
            if ( ! isset($env[$envKey]) ) $env[$envKey] = null;
        }

        $this->method = $method;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->fileData = $fileData;
        $this->env = $env;
        $this->body = $body;
        
        // Trim any leading slashes.
        $this->requestedUri = preg_replace('/^\/*/', '', $env['PATH_INFO']);
        
        if ( isset($env['REQUEST_URI']) ) {
            $requestUri = $env['REQUEST_URI'];
            $this->scriptPathRoot = $env['SCRIPT_PATH_ROOT'] = 
                preg_replace('/\/+/', '/', $this->requestedUri ?
                    substr($requestUri, 0, strpos($requestUri, $this->requestedUri)) :
                    $requestUri);
        } else {
            $this->scriptPathRoot = $env['SCRIPT_PATH_ROOT'] = '/';
        }
        
        // TODO: Does this actually make sense?
        $_SERVER = $this->env;

    }
    
    public function method() { return $this->method; }
    public function requestedUri() { return $this->requestedUri; }
    public function getRequestedUri() { return $this->deprecated()->requestedUri(); }
    public function getRequestedUrl() { return $this->deprecated()->requestedUri(); }
    
    public function scriptPathRoot() { return $this->scriptPathRoot; }
    
    public function queryParamExists($key) { return array_key_exists($key, $this->queryParams); }
    public function queryParam($key) { return isset($this->queryParams[$key]) ? $this->queryParams[$key] : null; }
    public function setQueryParam($key, $value = null) { $this->queryParams[$key] = $value; }
    public function unsetQueryParam($key) { unset($this->queryParams[$key]); }
    public function queryParams() { return $this->queryParams; }
    public function getQueryParams() { return $this->deprecated()->queryParams(); }

    public function postParamExists($key) { return array_key_exists($key, $this->postParams); }
    public function postParam($key) { return isset($this->postParams[$key]) ? $this->postParams[$key] : null; } 
    public function setPostParam($key, $value = null) { $this->postParams[$key] = $value; }
    public function unsetPostParam($key) { unset($this->postParams[$key]); }
    public function postParams() { return $this->postParams; }
    public function getPostParams() { return $this->deprecated()->postParams(); }
    
    public function attributeExists($key) { return array_key_exists($key, $this->attributes); }
    public function attribute($key) { return isset($this->attributes[$key]) ? $this->attributes[$key] : null; }
    public function setAttribute($key, $value = null) { $this->attributes[$key] = $value; }
    public function unsetAttribute($key) { unset($this->attributes[$key]); }
    public function attributes() { return $this->attributes; }
    public function attributeKeys() { return array_keys($this->attributes); }
    public function getAttributes() { return $this->deprecated()->attributes(); }
    public function getAttributeKeys() { return $this->deprecated()->attributeKeys(); }

    public function uriParamExists($key) { return array_key_exists($key, $this->uriParams); } 
    public function uriParam($key) { return isset($this->uriParams[$key]) ? $this->uriParams[$key] : null; }
    public function setUriParam($key, $value = null) { $this->uriParams[$key] = $value; }
    public function unsetUriParam($key) { unset($this->uriParams[$key]); }
    public function uriParams() { return $this->uriParams; }
    public function uriParamNames() { return array_keys($this->uriParams); }

    public function urlParamExists($key) { return $this->deprecated()->uriParamExists($key); }
    public function urlParam($key) { $this->deprecated()->uriParam($key); }
    public function setUrlParam($key, $value = null) { $this->deprecated()->setUriParam($key, $value); }
    public function unsetUrlParam($key) { $this->deprecated()->unsetUriParam($key); }
    public function urlParams() { $this->deprecated()->uriParams(); }
    public function getUrlParams() { $this->deprecated()->uriParams(); }
    public function urlParamNames() { $this->deprecated()->uriParamNames($key); }
    public function getUrlParamNames() { $this->deprecated()->uriParamNames($key); }
        
    public function fileParams() { return $this->fileParams; }
    public function fileParam($key, $secondKey = null) {
        if ( array_key_exists($key, $this->fileData) ) {
            $value = $this->fileData[$key];
            if ( $secondKey === null ) { return $value; }
            elseif ( array_key_exists($secondKey, $value) ) { return $value[$secondKey]; }
        }
        return null;
    }
    
    public function envExists($key) { return array_key_exists($key, $this->env); }
    public function envKeys() { return array_keys($this->env); }
    public function env($key) { return isset($this->env[$key]) ? $this->env[$key] : null; }
    public function envExport() { return $this->env; }
    
    public function body() { return $this->body; }
    
    public function getFileParams() { return $this->deprecated()->fileParams(); }
    public function getFileParam($key, $secondKey = null) { return $this->deprecated()->fileParam($key, $secondKey); }
    
    protected function deprecated() {
        if ( self::$LOGGER->isWarnEnabled() ) {
            $back = debug_backtrace();
            self::$LOGGER->warn('Deprecated call to ' . $back[1]['class'] . '::' . $back[1]['function'] . ', ' . $back[1]['file'] . ':' . $back[1]['line']);
        }
        return $this;
    }

}

halo_HttpRequest::$LOGGER = dd_logging_LogFactory::get('halo_HttpRequest');

