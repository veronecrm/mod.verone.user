<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Plugin;

use CRM\App\Module\Plugin;

class Links extends Plugin
{
    public function dashboard()
    {
        if($this->acl('mod.User.User', 'mod.User')->isAllowed('core.module'))
        {
            return [
                [
                    'ordering' => 0,
                    'icon' => 'fa fa-users',
                    'icon-type' => 'class',
                    'name' => $this->t('users'),
                    'href' => $this->createUrl('User', 'User')
                ]
            ];
        }
    }
}
