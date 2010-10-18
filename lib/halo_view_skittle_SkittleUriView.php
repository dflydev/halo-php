<?php

require_once('skittle_Skittle.php');
require_once('halo_view_skittle_AbstractSkittleView.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
require_once('halo_DispatcherUtil.php');

class halo_view_skittle_SkittleUriView extends halo_view_skittle_AbstractSkittleView {

    /**
     * Instantiate Skittle.
     * @param $model
     * @param $httpRequest
     * @param $httpResponse
     */
    public function instantiate(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        // Should default to Skittle's own classpath locator. Hopefully
        // this will be sufficient to load the incoming URI.
        return new skittle_Skittle();
    }

}