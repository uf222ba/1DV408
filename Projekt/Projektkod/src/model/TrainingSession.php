<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class TrainingSession
 * For storing information about a trainingg session
 * @package model
 */
class TrainingSession
{
    private $id;                //traningspassid
    private $date;              //traningspassdatum
    private $location;          //traningspassplats
    private $notes;             //anteckningar
    private $exerciseId;        //passdetaljerid
    private $repetitions;       //repetitioner
    private $succeededReps;     //lyckaderepetitioner
    private $environment;       //storning
    private $comments;          //kommentarer
    private $exerciseName;      //momentnamn

    /**
     * Getters and setters for all instance variables
     */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setNotes($notes) {
        $this->notes = $notes;
    }

    public function getExerciseId() {
        return $this->exerciseId;
    }

    public function setExerciseId($exerciseId) {
        $this->exerciseId = $exerciseId;
    }

    public function getRepetitions() {
        return $this->repetitions;
    }

    public function setRepetitions($repetitions) {
        $this->repetitions = $repetitions;
    }

    public function getSucceededReps() {
        return $this->succeededReps;
    }

    public function setSucceededReps($succeededReps) {
        $this->succeededReps = $succeededReps;
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function setEnvironment($environment) {
        $this->environment = $environment;
    }

    public function getComments() {
        return $this->comments;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function getExerciseName() {
        return $this->exerciseName;
    }

    public function setExerciseName($exerciseName) {
        $this->exerciseName = $exerciseName;
    }
}