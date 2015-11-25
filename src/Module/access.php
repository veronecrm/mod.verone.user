<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

return [
    [
        'name' => 'userPermissionsUsers',
        'id' => 'mod.User.User',
        'access' => [
            [
                'id' => 'core.module',
                'name' => 'auth.core.module'
            ],
            [
                'id' => 'core.read',
                'name' => 'auth.core.read'
            ],
            [
                'id' => 'core.write',
                'name' => 'auth.core.write'
            ],
            [
                'id' => 'core.delete',
                'name' => 'auth.core.delete'
            ],
            [
                'id' => 'core.edit.own',
                'name' => 'userPermissionEditOwnUserdata'
            ]
        ]
    ],
    [
        'name' => 'userPermissionsGroups',
        'id' => 'mod.User.Group',
        'access' => [
            [
                'id' => 'core.module',
                'name' => 'auth.core.module'
            ],
            [
                'id' => 'core.read',
                'name' => 'auth.core.read'
            ],
            [
                'id' => 'core.write',
                'name' => 'auth.core.write'
            ],
            [
                'id' => 'core.delete',
                'name' => 'auth.core.delete'
            ]
        ]
    ],
    [
        'name' => 'userPermissionsPermissions',
        'id' => 'mod.User.Permission',
        'access' => [
            [
                'id' => 'core.read',
                'name' => 'auth.core.read'
            ],
            [
                'id' => 'core.write',
                'name' => 'auth.core.write'
            ]
        ]
    ],
    [
        'name' => 'userAuthLog',
        'id' => 'mod.User.AuthLog',
        'access' => [
            [
                'id' => 'core.read',
                'name' => 'auth.core.read'
            ]
        ]
    ]
];
