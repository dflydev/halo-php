<?php
class halo_ModelAndView {
    protected $view;
    protected $viewName;
    protected $model;
    protected $isReferenced;
    public function __construct($viewMixed, $model = null) {
        if ( is_object($viewMixed) ) {
            $this->view = $viewMixed;
            $this->isReferenced = false;
        } else {
            $this->viewName = $viewMixed;
            $this->isReferenced = true;
        }
        $this->model = $model === null ? array() : $model;
    }
    public function isReferenced() {
        return $this->isReferenced;
    }
    public function getView() {
        return $this->view;
    }
    public function getViewName() {
        return $this->viewName;
    }
    public function getModel() {
        return $this->model;
    }
    public function keys() {
        return array_keys($this->model);
    }
    public function exists($key) {
        return array_key_exists($key, $this->model);
    }
    public function get($key) {
        return $this->model[$key];
    }
    public function set($key, $value = null) {
        $this->model[$key] = $value;
    }
}