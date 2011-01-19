<?php

require_once('dd_configuration_IConfiguration.php');
require_once('dd_configuration_CompositeConfiguration.php');

class halo_helper_ConfigurationHelper extends dd_configuration_CompositeConfiguration {
    
    /**
     * Print an HTML escaped configuration value
     */
    public function p() {
        $args = func_get_args();
        print htmlspecialchars(call_user_func_array(array($this, 'get'), $args));
    }
    
    /**
     * Print a configuration value
     */
    public function pRaw() {
        $args = func_get_args();
        print call_user_func_array(array($this, 'get'), $args);
    }

    /**
     * Get an HTML escaped configuration value
     */
    public function g() {
        $args = func_get_args();
        return htmlspecialchars(call_user_func_array(array($this, 'get'), $args));
    }
    
    /**
     * Get a configuration value
     */
    public function gRaw() {
        $args = func_get_args();
        return call_user_func_array(array($this, 'get'), $args);
    }
    
}