<?php

require_once('substrate_stones_IOrderedStone.php');
require_once('halo_HttpRequest.php');

interface halo_IHandlerMapping extends substrate_stones_IOrderedStone {

    /**
     * Get the handler execution chain
     * @param $httpRequest
     */
    public function getHandlerExecutionChain(halo_HttpRequest $httpRequest);

}