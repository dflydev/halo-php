<?php

require_once('dd_uri_Uri.php');

class halo_helper_UriHelper extends dd_uri_Uri {
    
    /**
     * Print an HTML escaped URI
     * @see dd_uri_Uri::get()
     */
    public function p() {
        $args = func_get_args();
        print htmlspecialchars(call_user_func_array(array($this, 'get'), $args));
    }
    
    /**
     * Print a URI
     * @see dd_uri_Uri::get()
     */
    public function pRaw() {
        $args = func_get_args();
        print call_user_func_array(array($this, 'get'), $args);
    }

    /**
     * Print an HTML escaped site URI
     * @see dd_uri_Uri::getSite()
     */
    public function pSite() {
        $args = func_get_args();
        print htmlspecialchars(call_user_func_array(array($this, 'getSite'), $args));
    }

    /**
     * Print a site URI
     * @see dd_uri_Uri::getSite()
     */
    public function pSiteRaw() {
        $args = func_get_args();
        print call_user_func_array(array($this, 'getSite'), $args);
    }
    
    /**
     * Get an HTML escaped URI
     * @see dd_uri_Uri::get()
     */
    public function g() {
        $args = func_get_args();
        return htmlspecialchars(call_user_func_array(array($this, 'get'), $args));
    }
    
    /**
     * Get a URI
     * @see dd_uri_Uri::get()
     */
    public function gRaw() {
        $args = func_get_args();
        return call_user_func_array(array($this, 'get'), $args);
    }
    
    /**
     * Get an HTML escaped site URI
     * @see dd_uri_Uri::getSite()
     */
    public function gSite() {
        $args = func_get_args();
        return htmlspecialchars(call_user_func_array(array($this, 'getSite'), $args));
    }

    /**
     * Get a site URI
     * @see dd_uri_Uri::getSite()
     */
    public function gSiteRaw() {
        $args = func_get_args();
        return call_user_func_array(array($this, 'getSite'), $args);
    }
    
}