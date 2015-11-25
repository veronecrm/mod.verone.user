<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

return [
    'mod.user.listener' => [
        'class' => 'App\Module\User\EventsListener',
        'arguments' => [
            'container'
        ],
        'listen' => [
            'onBeforeResponseSend'
        ]
    ]
];
