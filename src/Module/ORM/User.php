<?php
/**
 * Verone CRM | http://www.veronecrm.com
 *
 * @copyright  Copyright (C) 2015 - 2016 Adam Banaszkiewicz
 * @license    GNU General Public License version 3; see license.txt
 */

namespace App\Module\User\ORM;

use CRM\ORM\Entity;
use CRM\User\UserIdentityInterface;

class User extends Entity implements UserIdentityInterface
{
    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $group;
    protected $firstName;
    protected $lastName;
    protected $phone;
    protected $avatarUrl;
    protected $active;
    protected $forcePasswordChange;

    public function __construct($id = null, $username = null, $password = null)
    {
        if($id)
        {
            $this->id = $id;
        }

        if($username)
        {
            $this->setUsername($username);
        }

        if($password)
        {
            $this->setPassword($password);
        }
    }

    /**
     * Returns representation of real human name.
     * @return string
     */
    public function getName()
    {
        return $this->lastName.' '.$this->firstName;
    }

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
     * Gets the value of username.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the value of username.
     *
     * @param mixed $username the username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets the value of email.
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value of email.
     *
     * @param mixed $email the email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value of password.
     *
     * @param mixed $password the password
     *
     * @return self
     */
    public function setPassword($password)
    {
        if(strlen($password) > 1)
        {
            $this->password = $this->generatePassword($password);
        }

        return $this;
    }

    /**
     * Gets the group.
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Sets the $group.
     *
     * @param mixed $group the group
     *
     * @return self
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Gets the value of firstName.
     *
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the value of firstName.
     *
     * @param mixed $firstName the first name
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Gets the value of lastName.
     *
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the value of lastName.
     *
     * @param mixed $lastName the last name
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Gets the value of phone.
     *
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the value of phone.
     *
     * @param mixed $phone the phone
     *
     * @return self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Gets the value of avatarUrl.
     *
     * @return mixed
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * Sets the value of avatarUrl.
     *
     * @param mixed $avatarUrl the avatarUrl
     *
     * @return self
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Tells if given password match to the User's.
     * @param  string $password
     * @return boolean
     */
    public function passwordMatch($password)
    {
        return $this->password == $this->generatePassword($password);
    }

    /**
     * Generates password hash from given string.
     * @param  string $password
     * @return string
     */
    public function generatePassword($password)
    {
        return hash('sha256', $password);
    }

        /**
         * Gets the value of active.
         *
         * @return mixed
         */
        public function getActive()
        {
                return $this->active;
        }

        /**
         * Sets the value of active.
         *
         * @param mixed $active the active
         *
         * @return self
         */
        public function setActive($active)
        {
                $this->active = $active;

                return $this;
        }

        /**
         * Gets the value of forcePasswordChange.
         *
         * @return mixed
         */
        public function getForcePasswordChange()
        {
                return $this->forcePasswordChange;
        }

        /**
         * Sets the value of forcePasswordChange.
         *
         * @param mixed $forcePasswordChange the force password change
         *
         * @return self
         */
        public function setForcePasswordChange($forcePasswordChange)
        {
                $this->forcePasswordChange = $forcePasswordChange;

                return $this;
        }
}
