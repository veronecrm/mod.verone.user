<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\ORM;

use CRM\ORM\Repository;
use CRM\Pagination\PaginationInterface;

class UserRepository extends Repository implements PaginationInterface
{
    public $dbTable = '#__user';

    /**
     * @see CRM\Pagination\PaginationInterface::paginationCount()
     */
    public function paginationCount()
    {
        $request = $this->request();

        $query = [];
        $binds = [];

        if($request->get('letter'))
        {
            $query[] = 'lastName LIKE :lastName';
            $binds[':lastName'] = $request->get('letter').'%';
        }
        if($request->get('q'))
        {
            $query[] = '(username LIKE \'%'.$request->get('q').'%\' OR firstName LIKE \'%'.$request->get('q').'%\' OR lastName LIKE \'%'.$request->get('q').'%\')';
            $binds[':q'] = $request->get('q');
        }

        return $this->countAll(implode(' AND ', $query), $binds);
    }

    /**
     * @see CRM\Pagination\PaginationInterface::paginationGet()
     */
    public function paginationGet($start, $limit)
    {
        $request = $this->request();

        $query = [];
        $binds = [];

        if($request->get('letter'))
        {
            $query[] = 'lastName LIKE :lastName';
            $binds[':lastName'] = $request->get('letter').'%';
        }
        if($request->get('q'))
        {
            $query[] = '(username LIKE \'%'.$request->get('q').'%\' OR firstName LIKE \'%'.$request->get('q').'%\' OR lastName LIKE \'%'.$request->get('q').'%\')';
            $binds[':q'] = $request->get('q');
        }

        return $this->findAll(implode(' AND ', $query), $binds, $start, $limit);
    }

    public function findAll($conditions = '', array $binds = [], $start = null, $limit = null)
    {
        $pagination = '';

        if($start !== null && $limit !== null)
        {
            $pagination = "LIMIT $start, $limit";
        }

        if($conditions != '')
        {
            $conditions = "WHERE {$conditions}";
        }

        return $this->doPostSelect($this->prepareAndExecute("SELECT * FROM `{$this->dbTable}` {$conditions} ORDER BY lastName ASC {$pagination}", $binds, true));
    }

    public function findForLogin($username, $password)
    {
        return $this->selectQuery('SELECT * FROM #__user WHERE (username = :username OR email = :username) AND password = :password AND active = 1 LIMIT 1', [ ':username' => $username, ':password' => $this->entity('User', 'User')->generatePassword($password) ]);
    }

    /**
     * Search for user by given ID. If nothing found,
     * returns User object with parameters that tells
     * this user not exists.
     * @param  integer $id
     * @return User
     */
    public function findWithComplement($id)
    {
        $user = $this->find($id);

        if($user)
        {
            return $user;
        }
        else
        {
            return $this->entity('User', 'User')->setFirstName($this->t('userEmptyUser'));
        }
    }

    public function findInGroup($group)
    {
        return $this->findAll('`group` = :id', [ ':id' => $group ]);
    }

    public function removeUserSession($user)
    {
        $this->deleteQuery('DELETE FROM #__session WHERE owner = :id', [ ':id' => $user->getId() ]);
    }
}
