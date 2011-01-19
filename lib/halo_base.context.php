<?php

$context->add('halo.dispatcher', array(
    'className' => 'halo_Dispatcher',
    'dependencies' => array(
        'halo.helpers.systemRequestHelper',
    ),
));

$context->add('halo.controllerHandlerAdapter', array(
    'className' => 'halo_handler_ControllerHandlerAdapter',
));

$context->add('halo.helpers.systemRequestHelper', array(
    'className' => 'halo_helper_SystemRequestHelperFactory',
));
