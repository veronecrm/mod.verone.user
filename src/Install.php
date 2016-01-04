<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

use System\DependencyInjection\Container;
use App\Module\User\ORM\User;

class Install
{
    protected $container;

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function doInstallation()
    {
        if($this->container->has('_installationData'))
        {
            $data = $this->container->get('_installationData');

            if($data->installationType == 'crm')
            {
                $orm = $this->container->get('orm');

                $user = $orm->entity()->get('User', 'User');
                $user->setFirstName($data->firstName);
                $user->setLastName($data->lastName);
                $user->setPassword($data->password);
                $user->setUsername($data->username);
                $user->setEmail($data->email);
                $user->setGroup(1);

                $orm->repository()->get('User', 'User')->save($user);
            }
        }
    }
}
