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

class AuthLogRepository extends Repository implements PaginationInterface
{
    public $dbTable = '#__user_auth_log';

    /**
     * @see CRM\Pagination\PaginationInterface::paginationCount()
     */
    public function paginationCount()
    {
        $request = $this->request();

        $query = [];
        $binds = [];

        if($request->query->has('userId') && trim($request->get('userId')))
        {
            $query[] = 'userId = :userId';
            $binds[':userId'] = $request->get('userId');
        }
        if($request->query->has('loginFrom'))
        {
            $query[] = 'loginDate >= :loginFrom';
            $binds[':loginFrom'] = strtotime($request->get('loginFrom').' 00:00:00');
        }
        if($request->query->has('loginTo'))
        {
            $query[] = 'loginDate <= :loginTo';
            $binds[':loginTo'] = strtotime($request->get('loginTo').' 23:00:00');
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

        if($request->query->has('userId') && trim($request->get('userId')))
        {
            $query[] = 'userId = :userId';
            $binds[':userId'] = $request->get('userId');
        }
        if($request->query->has('loginFrom'))
        {
            $query[] = 'loginDate >= :loginFrom';
            $binds[':loginFrom'] = strtotime($request->get('loginFrom').' 00:00:00');
        }
        if($request->query->has('loginTo'))
        {
            $query[] = 'loginDate <= :loginTo';
            $binds[':loginTo'] = strtotime($request->get('loginTo').' 23:00:00');
        }

        return $this->selectQuery('SELECT * FROM #__user_auth_log '.($query === [] ? '' : 'WHERE '.implode(' AND ', $query))." ORDER BY loginDate DESC LIMIT $start, $limit", $binds);
    }

    public function fillUsernames($elements)
    {
        $repo = $this->repo('User');

        foreach($elements as $element)
        {
            $user = $repo->find($element->getUserId());

            if($user)
            {
                $element->setUserId($user->getName().' ('.$user->getId().')');
            }
        }
    }
}
