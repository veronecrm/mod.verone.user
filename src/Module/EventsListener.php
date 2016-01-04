<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User;

use System\DependencyInjection\Container;

class EventsListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onBeforeResponseSend($response)
    {
        $db       = $this->container->get('database');
        $sessions = $this->container->get('request')->getSession()->getRemovedSessions();

        foreach($sessions as $session)
        {
            $row = $db->query("SELECT * FROM #__user_auth_log WHERE sessionId = '".$session['id']."' ORDER BY loginDate DESC LIMIT 1");

            if(isset($row[0]))
            {
                $db->exec("UPDATE #__user_auth_log
                    SET
                        logoutDate = '".time()."',
                        logoutType = '1'
                    WHERE id = '{$row[0]['id']}'
                    LIMIT 1");
            }
            else
            {
                $db->exec("INSERT INTO #__user_auth_log
                    (
                        userId,
                        sessionId,
                        logoutDate,
                        logoutType
                    )
                    VALUES
                    (
                        '{$session['owner']}',
                        '{$session['id']}',
                        '".time()."',
                        '1'
                    )");
            }
        }
    }
}
