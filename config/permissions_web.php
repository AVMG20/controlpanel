<?php

return [
    '*',

    /*
    * Permissions for admin
    */
    'admin.*',

    'admin.roles.read',
    'admin.roles.write',

    'admin.users.read',
    'admin.users.write',

    /*
     * Permissions for settings
     */
    'settings.*',

    'settings.pterodactyl.read',
    'settings.pterodactyl.write',

    'settings.general.read',
    'settings.general.write',
];
