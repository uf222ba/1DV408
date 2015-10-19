<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("ModelList.php");
require_once("Exercise.php");

/**
 * Class ExercisesList
 * Inherits the base class ModelList
 * List for storing Exercise Class objects
 * @package model
 */
class ExercisesList extends ModelList {

    /**
    * Function for creating and returning an array that suits de Twig demands.
    * @return array the exerciseList as an associative array
    */
    public function toTwigArray() {
        $arrOfExercises = array();

        foreach($this->theList as $exercise) {
            $arrOfExercises[] = array('momentid' => $exercise->getId(),
                                     'momentnamn' => $exercise->getName());
        }
        return $arrOfExercises;
    }
}