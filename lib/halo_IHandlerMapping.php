<?php

interface halo_IHandlerMapping {

    /**
     * Get the handler execution chain
     * @param $httpRequest
     */
    public function getHandlerExecutionChain(halo_HttpRequest $httpRequest);

}