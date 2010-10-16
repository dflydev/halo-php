<?php

require_once('halo_IController.php');

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

class halo_handler_SimpleEchoController implements halo_IController {
    
    /**
     * Logger
     * @var dd_logging_ILogger
     */
    public static $LOGGER;
    
    /**
     * Content to render
     * @var unknown_type
     */
    protected $content;
    
    /**
     * Constructor
     * @param $content
     */
    public function __construct($content = null) {
        $this->content = $content;
    }
    
    /**
     * (non-PHPdoc)
     * @see halo_IController::handleRequest()
     */
    public function handleRequest(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        echo $this->content;
    }

}

halo_examples_SimpleEchoController::$LOGGER = dd_logging_LogFactory::get('halo_examples_SimpleEchoController');
