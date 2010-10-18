<?php

require_once('skittle_IHelperMapping.php');
require_once('halo_IHelperMapping.php');

class halo_helper_skittle_SkittleHelperMappingAdapter implements skittle_IHelperMapping {

    /**
     * Helper mapping
     * @var halo_IHelperMapping
     */
    protected $helperMapping;
    
    /**
     * Constructor
     * @param $helperMapping
     */
    public function __construct(halo_IHelperMapping $helperMapping) {
        $this->helperMapping = $helperMapping;
    }
    
    /**
     * Returns the helper object associated with the specified name.
     * @param string $name
     * @return object|null The helper object whose name is $name
     */
    public function helper($name) {
        return $this->helperMapping->helper($name);
    }

}
