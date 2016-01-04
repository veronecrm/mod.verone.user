<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Controller;

use Identicon\Identicon;
use CRM\App\Controller\BaseController;
use CRM\Pagination\Paginator;
use App\Module\User\ORM\User as UserEntity;

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

        /**
         * Save two times, 'couse we need user ID to create his avatar
         * as Identicon.
         */
        $this->repo()->save($user);
        $this->fillWithGeneratedAvatar($user);
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

        $editActionName = $request->query->get('act') == 'updateSelf' ? 'selfEdit' : 'edit';

        foreach([ 'username' => 'userUsernameExists', 'email' => 'userEmailExists' ] as $column => $message)
        {
            if($this->repo()->findAll("$column = :$column AND id != :id", [":$column" => $request->get($column), ':id' => $user->getId()]))
            {
                $this->flash('warning', $this->t($message));
                return $this->redirect('User', 'User', $editActionName, [ 'id' => $user->getId() ]);
            }
        }

        // We don't want to update password
        $request->request->remove('password');

        $user->fillFromRequest($request);

        if($request->request->get('avatarChangeActive') == 1)
        {
            if($request->request->get('avatarSource') == 1)
            {
                $this->fillWithGeneratedAvatar($user);
            }
            if($request->request->get('avatarSource') == 2)
            {
                $this->fillWithUploadedAvatarImage($user, 'avatarImage');
            }
            if($request->request->get('avatarSource') == 3)
            {
                $this->deleteAvatar($user);
                $user->setAvatarUrl($request->request->get('avatarImageUrl'));
            }
        }

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
                return $this->redirect('User', 'User', $editActionName, [ 'id' => $user->getId() ]);
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

        $this->deleteAvatar($user);
        $this->repo()->delete($user);

        $this->flash('success', $this->t('userDeletedSuccess'));

        return $this->redirect('User', 'User', 'index');
    }

    protected function fillWithGeneratedAvatar(UserEntity $user)
    {
        $basedir  = BASEPATH.'/web/modules/User/avatar';
        $filename = 'user-avatar-'.$user->getId().'-'.time().'.png';

        if(is_dir($basedir) == false)
        {
            mkdir($basedir, 0777, true);
        }

        file_put_contents($basedir.'/'.$filename, (new Identicon())->getImageData($user->getId(), 256));

        $this->deleteAvatar($user);
        $user->setAvatarUrl('modules/User/avatar/'.$filename);

        return $user;
    }

    protected function fillWithUploadedAvatarImage(UserEntity $user, $index)
    {
        if(isset($_FILES[$index]) && is_array($_FILES[$index]) && $_FILES[$index]['error'] != 4)
        {
            if(is_uploaded_file($_FILES[$index]['tmp_name']))
            {
                list($width, $height) = getimagesize($_FILES[$index]['tmp_name']);

                if($width == 0 || $height == 0)
                {
                    $this->flash('warning', $this->t('userUploadedFileIsntImage'));
                    return $user;
                }
                
                if($width > 300 || $height > 300)
                {
                    $this->flash('warning', $this->t('userMaxImageSizesRestriction'));
                    return $user;
                }

                $basedir  = BASEPATH.'/web/modules/User/avatar';
                $filename = 'user-avatar-'.$user->getId().'-'.time().'.'.pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION);

                move_uploaded_file($_FILES[$index]['tmp_name'], $basedir.'/'.$filename);

                $this->deleteAvatar($user);
                $user->setAvatarUrl('modules/User/avatar/'.$filename);
            }
        }

        return $user;
    }

    protected function deleteAvatar(UserEntity $user)
    {
        $basedir = BASEPATH.'/web/';

        if(is_file($basedir.$user->getAvatarUrl()))
        {
            unlink($basedir.$user->getAvatarUrl());
        }
    }
}
