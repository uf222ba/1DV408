<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("ModelList.php");
require_once("src/model/DAL/DogDAL.php");
require_once("Dog.php");

/**
 * Class DogList
 * Inherits base class ModelList
 * List for storing Dog Class objects
 * @package model
 */
class DogList extends ModelList
{
    private $dogDALObj;

    /**
     * Constructor that takes one parameter and are used for
     * populate the dogList instance.
     * @param integer $userId value of the current user
     */
    public function __construct($userId)
    {
        $this->dogDALObj = new DogDAL();
        $this->theList = $this->dogDALObj->getDogs($userId);
    }

    /**
     * Function for getting the dog that is set to be the chose
     * @return mixed the dog id
     */
    public function getDefaultDogId() {
        foreach($this->theList->theList as $dog) {
            if($dog->getIsChose())
                return $dog->getDogId();
        }
    }

    /**
     * Function for creating and returning an array that suits de Twig demands.
     * @return array the dogList as an associative array
     */
    public function toTwigArray()
    {
        $arrOfDogs = array();

        foreach($this->theList->theList as $dog) {
            $arrOfDogs[] = array("id" => $dog->getDogId(),
                                 "name" => $dog->getDogName(),
                                 "image" => $dog->getDogImage(),
                                 "isSelected" => $dog->getIsChose());
        }
        return $arrOfDogs;
    }

    /**
     * @param $userId
     */
    public function updateList($userId) {
        $this->theList = $this->dogDALObj->getDogs($userId);
    }
}
