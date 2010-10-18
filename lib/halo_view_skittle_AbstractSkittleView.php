<?php
require_once('halo_view_AbstractUriView.php');
require_once('halo_IHelperMappingAware.php');
require_once('halo_IRegisteredHelperNamesAware.php');
require_once('halo_IHelperMapping.php');
require_once('halo_helper_skittle_SkittleHelperMappingAdapter.php');
abstract class halo_view_skittle_AbstractSkittleView extends halo_view_AbstractUriView implements halo_IRegisteredHelperNamesAware, halo_IHelperMappingAware {

    protected $helperNames = array();
    
    protected $helperMapping;
    
    /**
     * Render
     * @param $model
     * @param $httpRequest
     * @param $httpResponse
     */
    public function render(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $skittle = $this->instantiate($model, $httpRequest, $httpResponse);
        if ( $this->helperMapping ) {
            $skittle->addHelperMapping(new halo_helper_skittle_SkittleHelperMappingAdapter($this->helperMapping));
        }
        if ( $this->helperNames ) {
            foreach ( $this->helperNames as $helperName ) {
                $skittle->addHelper($helperName);
            }
        }
        return $skittle->stringInc($this->uri, $model);
    }
    
    public function setRegisteredHelperNames(array $helperNames = null) {
        $this->helperNames = $helperNames;
    }
    
    public function setHelperMapping(halo_IHelperMapping $helperMapping) {
        $this->helperMapping = $helperMapping;
    }
        
    abstract public function instantiate(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse);
    
}