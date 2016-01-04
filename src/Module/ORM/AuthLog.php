<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\ORM;

use CRM\ORM\Entity;

class AuthLog extends Entity
{
    protected $id;
    protected $userId;
    protected $sessionId;
    protected $ip;
    protected $userAgent;
    protected $loginDate;
    protected $logoutDate;
    protected $logoutType;

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of userId.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the value of userId.
     *
     * @param mixed $userId the user id
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the value of sessionId.
     *
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets the value of sessionId.
     *
     * @param mixed $sessionId the session id
     *
     * @return self
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Gets the value of ip.
     *
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Sets the value of ip.
     *
     * @param mixed $ip the ip
     *
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Gets the value of userAgent.
     *
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Sets the value of userAgent.
     *
     * @param mixed $userAgent the user agent
     *
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Gets the value of loginDate.
     *
     * @return mixed
     */
    public function getLoginDate()
    {
        return $this->loginDate;
    }

    /**
     * Sets the value of loginDate.
     *
     * @param mixed $loginDate the login date
     *
     * @return self
     */
    public function setLoginDate($loginDate)
    {
        $this->loginDate = $loginDate;

        return $this;
    }

    /**
     * Gets the value of logoutDate.
     *
     * @return mixed
     */
    public function getLogoutDate()
    {
        return $this->logoutDate;
    }

    /**
     * Sets the value of logoutDate.
     *
     * @param mixed $logoutDate the logout date
     *
     * @return self
     */
    public function setLogoutDate($logoutDate)
    {
        $this->logoutDate = $logoutDate;

        return $this;
    }

    /**
     * Gets the value of logoutType.
     *
     * @return mixed
     */
    public function getLogoutType()
    {
        return $this->logoutType;
    }

    /**
     * Sets the value of logoutType.
     *
     * @param mixed $logoutType the logout type
     *
     * @return self
     */
    public function setLogoutType($logoutType)
    {
        $this->logoutType = $logoutType;

        return $this;
    }
}
