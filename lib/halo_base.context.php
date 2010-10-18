<?php

$context->add('halo.dispatcher', array(
    'className' => 'halo_Dispatcher',
    'dependencies' => array(
        'halo.helpers.systemHelpers',
    ),
));

$context->add('halo.controllerHandlerAdapter', array(
    'className' => 'halo_handler_ControllerHandlerAdapter',
));

$context->add('halo.helpers.systemHelpers', array(
    'className' => 'halo_helper_SystemRequestHelperFactory',
));