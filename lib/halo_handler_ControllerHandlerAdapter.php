<?php

require_once('halo_IHandlerAdapter.php');
require_once('halo_IController.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

class halo_ControllerHandlerAdapter implements halo_IHandlerAdapter {

    /**
     * (non-PHPdoc)
     * @see halo_IHandlerAdapter::supports()
     */
    public function supports($object) {
        return $object instanceof halo_IController;
    }

    /**
     * (non-PHPdoc)
     * @see halo_IHandlerAdapter::handle()
     */
    public function handle(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler) {
        return $handler->handleRequest($httpRequest, $httpResponse);
    }

}
