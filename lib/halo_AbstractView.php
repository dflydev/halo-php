<?php

require_once('halo_IView.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

/**
 * @deprecated
 */
abstract class halo_AbstractView implements halo_IView {
    
    /**
     * No longer needed
     * @param array $model
     * @param halo_HttpRequest $httpRequest
     * @param halo_HttpResponse $httpResponse
     * @deprecated
     */
    abstract public function doRender(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}
