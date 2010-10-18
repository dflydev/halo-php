<?php
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
interface halo_IRequestHelperFactory {
    public function supports($name);
    public function helper($name, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);
}