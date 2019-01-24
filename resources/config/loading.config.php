<?php

/**
 * Plugin autoloading configuration.
 */
return [
    'ZypeMedia\\Providers\\' => themosis_path('plugin.zypemedia.resources') . 'providers',
    'ZypeMedia\\Controllers\\' => themosis_path('plugin.zypemedia.resources') . 'controllers',
    'ZypeMedia\\Services\\' => themosis_path('plugin.zypemedia.resources') . 'services',
    'ZypeMedia\\Controllers\\Admin\\' => themosis_path('plugin.zypemedia.resources') . 'controllers/admin',
    'ZypeMedia\\Models\\' => themosis_path('plugin.zypemedia.resources') . 'models',
    'ZypeMedia\\Validators\\' => themosis_path('plugin.zypemedia.resources') . 'validators',
];
