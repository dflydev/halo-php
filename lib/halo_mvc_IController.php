<?php

/**
 * Halo Controller Interface.
 * @package halo
 */

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

/**
 * Halo Controller Interface.
 * @package halo
 */
interface halo_mvc_IController {

    /**
     * Handle request.
     *
     * Expected to handle the request and return a {@link halo_ModelAndView} object or
     * NULL if the output has already been handled.
     *
     * Implementing this interface is the only requirement of a Halo controller.
     *
     * @param halo_HttpRequest $httpRequest The request.
     * @param halo_HttpResponse $httpResponse The response.
     * @return halo_ModelAndView Model and View or NULL if output has already been taken care of.
     */
    public function handleRequest(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}