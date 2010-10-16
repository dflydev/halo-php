<?php

require_once('halo_IViewResolver.php');

require_once('substrate_IClassLoader.php');

abstract class halo_view_AbstractViewResolver implements halo_IViewResolver {
    
    /**
     * Class loader (for view classes)
     * @var substrate_IClassLoader
     */
    protected $classLoader;
    
    /**
     * The order for this stone
     * 
     * Used for sorting multiple stones of a given implementation.
     * @var int
     */
    private $stoneOrder;
    
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
    
    /**
     * Set the stone order
     * 
     * Used for sorting multiple stones of a given implementation.
     * @param $stoneOrder
     */
    public function setStoneOrder($stoneOrder) { $this->stoneOrder = $stoneOrder; }

    /**
     * Get the stone order
     * 
     * Used for sorting multiple stones of a given implementation.
     * @param $stoneOrder
     */
    public function getStoneOrder() { return $this->stoneOrder; }
    
}
