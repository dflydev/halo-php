<?php
interface halo_IHelperFactory {
    public function supports($name);
    public function helper($name);
}