<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\ORM;

use CRM\ORM\Repository;

class GroupRepository extends Repository
{
    public $dbTable = '#__user_group';

    public function getTree()
    {
        return $this->get('user.group.manager')->getTree();
    }

    public function updateParentsFromArray(array $data)
    {
        foreach($data as $id => $parent)
        {
            $this->updateQuery('UPDATE #__user_group SET `parent` = \''.$parent.'\' WHERE `id` = '.$id);
        }
    }
}
