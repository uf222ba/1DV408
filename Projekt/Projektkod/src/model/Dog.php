<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class Dog
 * Class for storing dog data stuff
 * @package model
 */
class Dog
{
    private $dogId;
    private $dogName;
    private $dogImage;
    private $isChose;

    /**
     * Dog class constructor
     * @param $id the dogId in the database
     * @param $name the dogs name
     * @param $img name of a image file
     * @param $chose is this the dog of choice
     */
    public function __construct($id, $name, $img, $chose) {
        $this->dogId = $id;
        $this->dogName = $name;
        $this->dogImage = $img;
        $this->isChose = $chose;
    }

    /**
     * Getters and setters for all instance variables
     */

    public function setDogId($dogId) {
        $this->dogId = $dogId;
    }

    public function setDogName($dogName) {
        $this->dogName = $dogName;
    }

    public function getDogId() {
        return $this->dogId;
    }

    public function getDogName() {
        return $this->dogName;
    }

    public function getDogImage() {
        return $this->dogImage;
    }

    public function getIsChose()
    {
        return $this->isChose;
    }

    public function setIsChose($isChose)
    {
        $this->isChose = $isChose;
    }
}