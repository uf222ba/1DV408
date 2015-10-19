<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once('DogList.php');

/**
 * Class Trainer inherits the User Class
 * Class for storing the dog trainer stuff
 * @package model
 */
class Trainer extends User
{
    private $defaultDogId;
    private $dogs;

    /**
     * Copy constructor
     * @param User $userObject
     */
    public function __construct(User $userObject) {
        $this->username = $userObject->getUsername();
        $this->password = $userObject->getPassword();
        $this->userId = $userObject->getUserId();
        $this->firstName = $userObject->getFirstName();
        $this->lastName = $userObject->getLastName();
        $this->dogs = new DogList($this->userId);
        $this->defaultDogId = $this->dogs->getDefaultDogId();
    }

    public function getDogs() {
        return $this->dogs;
    }

    public function getDefaultDogId() {
        return $this->defaultDogId;
    }

    /**
     * Function for setting the default dog from the dogList
     */
    public function setDefaultDogId() {
        $this->defaultDogId = $this->dogs->getDefaultDogId();
    }

    /**
     * Function for updating the list of dogs
     */
    public function updateDogs() {
        $this->dogs->updateList($this->userId);
    }
}