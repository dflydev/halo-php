<?php

require_once('skittle_Skittle.php');

require_once('halo_AbstractView.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
require_once('halo_DispatcherUtil.php');

class halo_SkittleView extends halo_AbstractView {
    public function doRender(array $model, halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $context = halo_DispatcherUtil::GET_CONTEXT($httpRequest);
        if ( $context->exists('skittle.pathResourceLocator') ) {
            $skittle = new skittle_Skittle($context->get('skittle.pathResourceLocator'));
        } elseif ( $stones = $context->findStonesByImplementation('skittle_IResourceLocator') ) {
            $skittle = new skittle_Skittle($stones[0]);
        } else {
            $skittle = new skittle_Skittle();
        }
        return $skittle->stringInc($this->uri, $model);
    }
}