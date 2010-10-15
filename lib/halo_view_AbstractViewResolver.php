<?php

require_once('halo_view_IViewResolver.php');

require_once('substrate_IClassLoader.php');

abstract class halo_view_AbstractViewResolver implements halo_view_IViewResolver {
    
    /**
     * Class loader (for view classes)
     * @var substrate_IClassLoader
     */
    protected $classLoader;
    
    /**
     * Constructor
     * @param $classLoader
     */
    public function __construct(substrate_IClassLoader $classLoader = null) {
        if ( $classLoader === null ) {
            require_once('substrate_ClasspathClassLoader.php');
            $this->classLoader = new substrate_ClasspathClassLoader();
        } else {
            $this->classLoader = $classLoader;
        }
    }
    
}
