<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\Plugin;

use CRM\App\Module\Plugin;

class User extends Plugin
{
    public function login($userId)
    {
        $session = $this->request()->getSession();

        $this->db()->exec("INSERT INTO #__user_auth_log
            (
                userId,
                sessionId,
                ip,
                userAgent,
                loginDate
            )
            VALUES
            (
                '{$session->getOwner()}',
                '{$session->getId()}',
                '".(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0')."',
                '".(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'UNKNOWN')."',
                '".time()."'
            )");
    }

    public function logout($userId)
    {
        $row = $this->db()->query("SELECT * FROM #__user_auth_log WHERE sessionId = '".$this->request()->getSession()->getId()."' ORDER BY loginDate DESC LIMIT 1");

        if(isset($row[0]))
        {
            $this->db()->exec("UPDATE #__user_auth_log
                SET
                    logoutDate = '".time()."',
                    logoutType = '2'
                WHERE id = '{$row[0]['id']}'
                LIMIT 1");
        }
    }

    public function sessionsRemovedByOverwrite($sessions)
    {
        foreach($sessions as $item)
        {
            $row = $this->db()->query("SELECT * FROM #__user_auth_log WHERE sessionId = '".$item['id']."' ORDER BY loginDate DESC LIMIT 1");

            if(isset($row[0]))
            {
                $this->db()->exec("UPDATE #__user_auth_log
                    SET
                        logoutDate = '".time()."',
                        ip = '".(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0')."',
                        userAgent = '".(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'UNKNOWN')."',
                        logoutType = '3'
                    WHERE id = '{$row[0]['id']}'
                    LIMIT 1");
            }
            else
            {
                $this->db()->exec("INSERT INTO #__user_auth_log
                (
                    userId,
                    sessionId,
                    loginDate,
                    ip,
                    userAgent,
                    logoutType
                )
                VALUES
                (
                    '{$item['owner']}',
                    '{$item['id']}',
                    '".time()."',
                    '".(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0')."',
                    '".(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'UNKNOWN')."',
                    '3'
                )");
            }
        }
    }
}
