<?php

require_once('halo_IView.php');

require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');

abstract class halo_AbstractView implements halo_IView {
    
    protected $viewName;

    protected $uri;
    
    public function __construct($viewName = null, $uri = null) {
        $this->viewName = $viewName;
        $this->uri = $uri;
    }

    final public function render(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        return $this->doRender($model, $httpRequest, $httpResponse);
    }
    
    abstract public function doRender(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);

}
