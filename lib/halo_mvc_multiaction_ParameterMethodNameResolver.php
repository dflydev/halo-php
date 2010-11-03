<?php

require_once('halo_mvc_multiaction_IMethodNameResolver.php');
require_once('halo_HttpRequest.php');

class halo_mvc_multiaction_ParameterMethodNameResolver implements halo_mvc_multiaction_IMethodNameResolver {
    
    /**
     * Default method name (if no others are specified)
     * @var string
     */
    protected $defaultMethodName = 'action';

    /**
     * Param name if other than default method name (action)
     * @var string
     */
    private $paramName;

    /**
     * Array of param names other than default method name (action)
     * @var Array
     */
    private $methodParamNames;

    /**
     * Constructor
     * @param $paramName
     * @param $methodParamNames
     */
    public function __construct($paramName = null, Array $methodParamNames = null) {
        $this->paramName = $paramName;
        $this->methodParamNames = $methodParamNames;
    }

    /**
     * Set the param name
     * @param $paramName
     */
    public function setParamName($paramName) {
        $this->paramName = $paramName;
    }

    /**
     * Set the param names
     * @param $methodParamNames
     */
    public function setMethodParamNames(Array $methodParamNames) {
        $this->methodParamNames = $methodParamNames;
    }

    /**
     * (non-PHPdoc)
     * @see halo_mvc_multiaction_IMethodNameResolver::getHandlerMethodName()
     */
    public function getHandlerMethodName(halo_HttpRequest $request) {

        $methodName = '';
        
        if ($this->methodParamNames !== null) {
            foreach ($this->methodParamNames as $variable) {
                if($request->queryParam($variable) !== null){
                    $methodNamme = $request->queryParam($variable);
                    break;
                }
            }
        }

        if ($methodName === null && $this->paramName !== null) {
            $methodName = $request->queryParam($this->paramName);
        }

        if ($methodName !== null && $methodName === "") {
            $methodName = null;
        }

        if ($methodName === null) {
            if ($this->defaultMethodName !== null) {
                $methodName = $this->defaultMethodName;
            }
            else {
                throw new Exception('Handler method, not found');
            }
        }
        
        return $methodName;
        
    }
    
}