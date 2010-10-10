<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
require_once('halo_ModelAndView.php');

interface halo_IHandlerInterceptor {
    
    /**
     * Execute before handler is called
     * @param $httpRequest
     * @param $httpResponse
     * @param $handler
     */
    public function preHandle(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler);
    
    /**
     * Execute after handler is called
     * @param $httpRequest
     * @param $httpResponse
     * @param $handler
     * @param $modelAndView
     */
    public function postHandle(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler, halo_ModelAndView $modelAndView);
    
    /**
     * Execute after execution is completed (read: FINALLY)
     * @param $httpRequest
     * @param $httpResponse
     * @param $handler
     * @param $e
     */
    public function afterCompletion(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler, Exception $e);
    
}