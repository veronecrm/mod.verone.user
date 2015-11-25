<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Controller;

use CRM\App\Controller\BaseController;
use CRM\Pagination\Paginator;

/**
 * @section mod.User.User
 */
class User extends BaseController
{
    /**
     * @access core.module
     */
    public function indexAction($request)
    {
        $paginator = new Paginator($this->repo(), $request->get('page'), $this->createUrl($this->request()->query->all()));

        return $this->render('', [
            'elements'    => $paginator->getElements(),
            'pagination'  => $paginator
        ]);
    }

    /**
     * @access core.write
     */
    public function addAction()
    {
        return $this->render('add', [
            'groups' => $this->repo('Group')->getTree(),
            'user'   => $this->entity()
        ]);
    }

    /**
     * @access core.write
     */
    public function saveAction($request)
    {
        foreach([ 'username' => 'userUsernameExists', 'email' => 'userEmailExists' ] as $column => $message)
        {
            if($this->repo()->findAll("$column = :$column", [":$column" => $request->get($column)]))
            {
                $this->flash('warning', $this->t($message));
                return $this->redirect('User', 'User', 'add');
            }
        }

        if($request->get('password') != $request->get('passwordRepeat'))
        {
            $this->flash('warning', $this->t('userPasswordsMustBeTheSame'));
            return $this->redirect('User', 'User', 'add');
        }

        if($request->request->get('password') != $request->request->get('passwordRepeat'))
        {
            $this->flash('warning', $this->t('userPasswordsMustBeTheSame'));
            return $this->redirect('User', 'User', 'add');
        }

        $user = $this->entity()->fillFromRequest($request);
        $user->setPassword($request->request->get('password'));

        $this->repo()->save($user);

        $this->eventDispatcher()->dispatch('onUserAdd', [$user]);

        $this->flash('success', $this->t('userUserSavedSuccess'));

        if($request->get('apply'))
            return $this->redirect('User', 'User', 'edit', [ 'id' => $user->getId() ]);
        else
            return $this->redirect('User', 'User', 'index');
    }

    /**
     * @access core.read
     */
    public function editAction($request)
    {
        return $this->edit($request, 'edit');
    }

    /**
     * @access core.edit.own
     */
    public function selfEditAction($request)
    {
        $request->query->set('id', $this->user()->getId());

        return $this->edit($request, 'selfEdit');
    }

    protected function edit($request, $type)
    {
        $user = $this->repo()->find($request->get('id'));

        if(! $user)
        {
            $this->flash('danger', $this->t('userUserDoesntExists'));
            return $this->redirect('User', 'User', 'index');
        }

        return $this->render($type, [
            'user' => $user,
            'groups' => $this->repo('Group')->getTree()
        ]);
    }

    /**
     * @access core.edit.own
     */
    public function updateSelfAction($request)
    {
        /**
         * In self edit mode, we must not update below values!!!!!
         */
        $request->request
            ->remove('username')
            ->remove('active')
            ->remove('group');

        return $this->update($request);
    }

    /**
     * @access core.write
     */
    public function updateAction($request)
    {
        return $this->update($request);
    }

    protected function update($request)
    {
        $user = $this->repo()->find($request->get('id'));

        if(! $user)
        {
            $this->flash('danger', $this->t('userUserDoesntExists'));
            return $this->redirect('User', 'User', 'index');
        }

        foreach([ 'username' => 'userUsernameExists', 'email' => 'userEmailExists' ] as $column => $message)
        {
            if($this->repo()->findAll("$column = :$column AND id != :id", [":$column" => $request->get($column), ':id' => $user->getId()]))
            {
                $this->flash('warning', $this->t($message));
                return $this->redirect('User', 'User', 'edit', [ 'id' => $user->getId() ]);
            }
        }

        // We don't want to update password
        $request->request->remove('password');

        $user->fillFromRequest($request);

        $this->repo()->save($user);

        $this->eventDispatcher()->dispatch('onUserUpdate', [$user]);

        /**
         * If user is set to inactive, we remove his session what automatically
         * logged him out.
         */
        if($user->getForcePasswordChange())
        {
            $this->repo()->removeUserSession($user);
        }

        if($request->isAJAX())
        {
            return $this->responseAJAX([
                'status'  => 'success',
                'message' => $this->t('userUserSavedSuccess')
            ]);
        }
        else
        {
            $this->flash('success', $this->t('userUserSavedSuccess'));

            if($request->get('apply'))
                return $this->redirect('User', 'User', 'edit', [ 'id' => $user->getId() ]);
            else
                return $this->redirect('User', 'User', 'index');
        }
    }

    /**
     * @access core.write
     */
    public function changePasswordAction($request)
    {
        $params = [
            'mod' => 'User',
            'cnt' => 'User'
        ];

        if($request->request->get('selfEdit'))
        {
            $params['act'] = 'selfEdit';
        }
        else
        {
            $params['act'] = 'edit';
            $params['id']  = $request->request->get('id');
        }
        if($request->request->get('forcePasswordChange'))
        {
            $params['forcePasswordChange'] = '1';
        }


        if($params['act'] == 'selfEdit')
        {
            $user = $this->repo()->find($this->user()->getId());
        }
        else
        {
            $user = $this->repo()->find($request->request->get('id'));
        }

        if(! $user)
        {
            $this->flash('danger', $this->t('userUserDoesntExists'));
            return $this->redirect('User', 'User', 'index');
        }

        if($request->request->get('password') != $request->request->get('passwordRepeat'))
        {
            $this->flash('warning', $this->t('userPasswordsMustBeTheSame'));
            return $this->redirect($params);
        }

        $user->setPassword($request->request->get('password'));
        $user->setForcePasswordChange(0);

        $this->repo()->save($user);

        $this->eventDispatcher()->dispatch('onUserUpdate', [$user]);

        if(isset($params['forcePasswordChange']))
        {
            $params = [
                'mod' => 'Home',
                'cnt' => 'Home',
                'act' => 'index'
            ];
        }

        $this->flash('success', $this->t('userPasswordsChanged'));
        return $this->redirect($params);
    }

    /**
     * @access core.delete
     */
    public function deleteAction($request)
    {
        $user = $this->repo()->find($request->get('id'));

        if(! $user)
        {
            $this->flash('danger', $this->t('userUserDoesntExists'));
            return $this->redirect('User', 'User', 'index');
        }

        $this->eventDispatcher()->dispatch('onUserDelete', [$user]);

        $this->repo()->delete($user);

        $this->flash('success', $this->t('userDeletedSuccess'));

        return $this->redirect('User', 'User', 'index');
    }
}
