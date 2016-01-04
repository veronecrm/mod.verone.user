<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Controller;

use CRM\App\Controller\BaseController;
use CRM\Pagination\Paginator;

/**
 * @section mod.User.AuthLog
 */
class AuthLog extends BaseController
{
    /**
     * @access core.read
     */
    public function indexAction($request)
    {
        $repo = $this->repo('AuthLog');

        $paginator = new Paginator($repo, $request->get('page'), $this->createUrl($this->request()->query->all()));
        $elements  = $paginator->getElements();

        $repo->fillUsernames($elements);

        return $this->render('', [
            'elements'    => $elements,
            'pagination'  => $paginator,
            'users'       => $this->repo('user')->findAll()
        ]);
    }
}
