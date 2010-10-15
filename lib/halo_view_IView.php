<?php

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

interface halo_view_IView {

    /**
     * Render model
     * @param $model
     * @param $httpRequest
     * @param $httpResponse
     */
    public function render(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}