<?php

require_once('halo_IRequestHelperFactory.php');
class halo_helper_SystemRequestHelperFactory implements halo_IRequestHelperFactory {
    protected $helperNames = array('httpRequest', 'httpResponse');
    public function supports($name) {
        return in_array($name, $this->helperNames);
    }
    public function helper($name, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        switch($name) {
            case 'httpRequest':
                return $httpRequest;
                break;
            case 'httpResponse':
                return $httpResponse;
                break;
            default:
                return null;
                break;
        }
    }
}