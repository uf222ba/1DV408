<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class Exercise
 * Class for storing a dog training exercise
 * @package model
 */
class Exercise {

    private $id;
    private $name;
    private $command;
    private $description;

    public function __construct() {
        $this->id = null;
        $this->name = null;
        $this->command = null;
        $this->description = null;
    }

    /**
     * Getters and setters for all instance variables
     */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCommand() {
        return $this->command;
    }

    public function setCommand($command) {
        $this->command = $command;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}