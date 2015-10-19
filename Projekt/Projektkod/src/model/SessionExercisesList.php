<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("ModelList.php");
require_once("TrainingSession.php");

/**
 * Class SessionExercisesList
 * Inherits the base class ModelList
 * List for storing TrainingSession Class objects
 * @package model
 */
class SessionExercisesList extends ModelList
{
    /**
     * Function for creating and returning an array that suits de Twig demands.
     * @return array the sessionExerciseList as an associative array
     */
    public function toTwigArray()
    {
        $arrOfExercises = array();

        foreach ($this->theList as $exercise) {
            $arrOfExercises[] = array('traningspassid' => $exercise->getId(),
                                      'traningspassdatum' => $exercise->getDate(),
                                      'traningspassplats' => $exercise->getLocation(),
                                      'anteckningar' => $exercise->getNotes(),
                                      'passdetaljerid' => $exercise->getExerciseId(),
                                      'repetitioner' => $exercise->getRepetitions(),
                                      'lyckaderepetitioner' => $exercise->getSucceededReps(),
                                      'storning' => $exercise->getEnvironment(),
                                      'kommentarer' => $exercise->getComments(),
                                      'momentnamn' => $exercise->getExerciseName());
        }
        return $arrOfExercises;
    }
}