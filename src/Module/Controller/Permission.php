<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Controller;

use CRM\App\Controller\BaseController;

/**
 * @section mod.User.Permission
 */
class Permission extends BaseController
{
    /**
     * @access core.read
     */
    public function indexAction($request)
    {
        if(! $request->get('module'))
        {
            return $this->redirect('User', 'Permission', 'index', [ 'module' => 'Settings' ]);
        }

        $modules = [];

        foreach($this->get('package.module.manager')->all() as $module)
        {
            if(is_file($module->getRoot().'/access.php'))
            {
                $modules[] = $module;
            }
        }

        return $this->render('', [
            'modules'       => $modules,
            'currentModule' => $request->get('module'),
            'generator'     => $this->get('permission.acl.generator')
        ]);
    }

    /**
     * @access core.write
     */
    public function saveAction($request)
    {
        $acl = $this->get('permission.acl');
        $module = $request->get('module');

        foreach($request->request->get('acl') as $section => $val1)
        {
            foreach($val1 as $access => $val2)
            {
                foreach($val2 as $group => $permission)
                {
                    $opened = $acl->open(str_replace('_', '.', $section), 'mod.'.$module, $group);

                    switch($permission)
                    {
                        case 0: $opened->inherit(str_replace('_', '.', $access),  'mod.'.$module, $group); break;
                        case 1: $opened->deny(str_replace('_', '.', $access),     'mod.'.$module, $group); break;
                        case 2: $opened->allow(str_replace('_', '.', $access),    'mod.'.$module, $group); break;
                    }
                }
            }
        }

        $this->flash('success', $this->t('userPermissionsSaved'));
        return $this->redirect('User', 'Permission', 'index', [ 'module' => $module ]);
    }
}
