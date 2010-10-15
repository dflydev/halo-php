<?php

require_once('halo_IView.php');

abstract class halo_view_AbstractUriView implements halo_IView {
    
    /**
     * URI
     * @var string
     */
    protected $uri;
    
    /**
     * Constructor
     * @param $uri
     */
    public function __construct($uri) {
        $this->uri = $uri;
    }

}