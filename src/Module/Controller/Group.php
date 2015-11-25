<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Controller;

use CRM\App\Controller\BaseController;

/**
 * @section mod.User.Group
 */
class Group extends BaseController
{
    /**
     * @access core.module
     */
    public function indexAction()
    {
        return $this->render('', [
            'groups' => $this->repo('Group')->getTree()
        ]);
    }
    
    /**
     * @access core.write
     */
    public function addAction()
    {
        return $this->render('form', [
            'groups' => $this->repo('Group')->getTree(),
            'group'  => $this->entity('Group')
        ]);
    }

    /**
     * @access core.write
     */
    public function saveAction($request)
    {
        $group = $this->entity('Group')->fillFromRequest($request);
        $this->repo('Group')->save($group);

        $this->flash('success', $this->t('userGroupSavedSuccess'));

        if($request->get('apply'))
        {
            return $this->redirect('User', 'Group', 'edit', [ 'id' => $group->getId() ]);
        }
        else
        {
            return $this->redirect('User', 'Group', 'index');
        }
    }

    /**
     * @access core.read
     */
    public function editAction($request)
    {
        $group = $this->repo('Group')->find($request->get('id'));

        if(! $group)
        {
            $this->flash('danger', $this->t('userGroupDoesntExists'));
            return $this->redirect('User', 'Group', 'index');
        }

        return $this->render('form', [
            'group' => $group,
            'users' => $this->repo('User')->findInGroup($group->getId())
        ]);
    }

    /**
     * @access core.write
     */
    public function updateAction($request)
    {
        $group = $this->repo('Group')->find($request->get('id'));

        if(! $group)
        {
            $this->flash('danger', $this->t('userGroupDoesntExists'));
            return $this->redirect('User', 'Group', 'index');
        }

        $group->fillFromRequest($request);

        $this->repo('Group')->save($group);

        $this->flash('success', $this->t('userGroupSavedSuccess'));

        if($request->get('apply'))
        {
            return $this->redirect('User', 'Group', 'edit', [ 'id' => $group->getId() ]);
        }
        else
        {
            return $this->redirect('User', 'Group', 'index');
        }
    }

    /**
     * @access core.delete
     */
    public function deleteAction($request)
    {
        $group = $this->repo('Group')->find($request->get('id'));

        if(! $group)
        {
            $this->flash('danger', $this->t('userGroupDoesntExists'));
            return $this->redirect('User', 'Group', 'index');
        }

        if($count = count($this->repo('User')->findInGroup($group->getId())))
        {
            $this->flash('danger', sprintf($this->t('userGroupDeleteErrorHasUsers'), $count));
            return $this->redirect('User', 'Group', 'index');
        }

        $this->repo('Group')->delete($group);

        $this->flash('success', $this->t('userGroupDeletedSuccess'));

        return $this->redirect('User', 'Group', 'index');
    }

    /**
     * @access core.write
     */
    public function updateTreeAction($request)
    {
        $this->repo('Group')->updateParentsFromArray($request->get('parents'));

        $this->flash('success', $this->t('userGroupTreeChanged'));
        return $this->redirect('User', 'Group', 'index');
    }
}
