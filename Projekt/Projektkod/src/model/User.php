<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class User
 * Base class
 * Class for storing user data
 * @package model
 */
class User
{
    protected $userId;
    protected $username;
    protected $password;
    protected $firstName;
    protected $lastName;

    public function __construct() {
        $this->username = null;
        $this->password = null;
        $this->userId = null;
        $this->firstName = null;
        $this->lastName = null;
    }

    /**
     * Function for setting the instance variables
     * @param $userName
     * @param $password
     * @param $userId
     * @param $firstName
     * @param $lastName
     */
    public function setUser($userName, $password, $userId, $firstName, $lastName) {
        $this->username = $userName;
        $this->password = $password;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Getters and setters for the instance variables
     */

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUsername() {
        if(strlen($this->username == 0 && Session::getUserName() != null))
            $this->username = $_SESSION["username"];
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }

    /**
     * Function for deleting the instance variables stored information
     */
    public function deleteUserCredentials() {
        $this->username = "";
        $this->password = "";
        $this->firstName = "";
        $this->lastName = "";
        $this->userId = "";
    }

    /**
     * Function for returning the name and userId as an associative array
     * @return array containing "userFullName" and "userId"
     */
    public function getUserInformation() {
        $userInfo = array("userFullName" => $this->firstName . " " . $this->lastName,
                          "userId" => $this->userId);
        return $userInfo;
    }
}