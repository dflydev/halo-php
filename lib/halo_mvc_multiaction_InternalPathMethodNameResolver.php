<?php

require_once('halo_mvc_multiaction_IMethodNameResolver.php');
require_once('halo_HttpRequest.php');

class halo_mvc_multiaction_InternalPathMethodNameResolver implements halo_mvc_multiaction_IMethodNameResolver {

    private $prefix = "";
    private $suffix = "";

    public function setPrefix($prefix) {
        $this->prefix = ($prefix !== null ? $prefix : "");
    }
    protected function getPrefix() {
        return $this->prefix;
    }
    public function setSuffix($suffix) {
        $this->suffix = ($suffix !== null ? $suffix : "");
    }
    protected function getSuffix() {
        return $this->suffix;
    }

    /**
     * (non-PHPdoc)
     * @see halo_mvc_multiaction_IMethodNameResolver::getHandlerMethodName()
     */
    public function getHandlerMethodName(halo_HttpRequest $request){
        $urlPath = $request->getRequestedUri();
        $methodName = basename($urlPath, ".php");
        $methodName = $this->prefix. $methodName. $this->suffix;
        return $methodName;
    }
    
}