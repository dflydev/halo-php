<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_mvc_multiaction_MultiActionController implements halo_mvc_IController {
    
    /**
     * Method Name Resolver
     * @var halo_mvc_multiaction_IMethodNameResolver
     */
    private $methodNameResolver;
    
    /**
     * Resolved method name to be called
     * @var string
     */
    private $delegate;

    /**
     * Constructor
     * Enter description here ...
     */
    public function __construct(halo_mvc_multiaction_IMethodNameResolver $methodNameResolver = null, $delegate = null) {
        if ( $methodNameResolver === null ) {
            require_once('halo_mvc_multiaction_InternalPathMethodNameResolver.php');
            $methodNameResolver = new halo_mvc_multiaction_InternalPathMethodNameResolver();
        }
        $this->methodNameResolver = $methodNameResolver;
        $this->delegate = $delegate === null ? $this : $delegate;
    }

    /**
     * (non-PHPdoc)
     * @see halo_mvc_IController::handleRequest()
     */
    public function handleRequest(halo_HttpRequest $request, halo_HttpResponse $response){
        $methodName = $this->methodNameResolver->getHandlerMethodName($request);
        return $this->invokeNamedMethod($methodName, $request, $response);
    }

    public function setDelegate($delegate) {
        $this->delegate = $delegate;
    }
    
    public function setMethodNameResolver(halo_mvc_multiaction_IMethodNameResolver $methodNameResolver) {
        $this->methodNameResolver = $methodNameResolver;
    }
    
    public function getMethodNameResolver() {
        return $this->methodNameResolver;
    }

    /**
     * Bind request parameters onto the given command stone
     * @param $request
     * @param $object
     */
    public function bind(halo_HttpRequest $request, $object){
        $reflectionClass = new ReflectionClass(get_class($object));

        foreach ($request->getParameterNames() as $param) {
            $props = explode(".",$param);
            $value = $object;
            for ($index = 0; $index <= count($props)-2; $index++) {
                $prop = 'get'.ucfirst($props[$index]);
                if(method_exists($value , $prop)){
                    $value = $value->$prop();
                } else {
                    break;
                }
            }

            $prop = 'set'.ucfirst($props[count($props)-1]);
            if(method_exists($value , $prop)){
                $value->$prop($request->getParameter($param));
                $prop = 'get'.ucfirst($props[count($props)-1]);
            }
        }
    }

    /**
     * Invoke the method name on the delegate
     * @param string $methodName
     * @param halo_HttpRequest $request
     * @param halo_HttpResponse $response
     */
    protected function invokeNamedMethod($methodName, halo_HttpRequest $request, halo_HttpResponse $response){

        $method = new ReflectionMethod(get_class($this->delegate), $methodName);
        $parameters = $method->getParameters();
        $params = Array();
        $params[] = $request;
        $params[] = $response;

        if ($method->getNumberOfParameters() >= 3) {
            $commandReflectionClass = new ReflectionClass($parameters[2]->getClass());
            $class = $parameters[2]->getClass()->getName();
            $command = new $class;

            $this->bind($request, $command);
            $params[] = $command;
        }

        $returnValue = $method->invokeArgs($this->delegate, $params);
        return $returnValue;
    }

}