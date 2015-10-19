<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class TwigArray
 * Class with functions for building arrays that suits the twig render function in the view
 * @package model
 */
class TwigArray
{
    private $trainer;
    private $twigArray;

    const USERFULLNAME = "userFullName";
    const DOGS = "dogs";
    const PASS = "pass";
    const DATE = "date";
    const LOCATION = "location";
    const EXERCISES = "exercises";

    /**
     * Constructor the creates the skeleton array
     * The functions can then add the function specific data
     * @param Trainer $trainerObj
     */
    public function __construct(Trainer $trainerObj) {
        $this->trainer = $trainerObj;
        $this->twigArray = array(self::USERFULLNAME => $this->trainer->getFullName(),
                                 self::DOGS => $this->trainer->getDogs()->toTwigArray());
    }

    /**
     * Function for adding all the training sessions for a specific user and a specific dog
     * Used for the table in the startpage
     * @param TrainingSessionsList $trainingSessionList
     * @return array with twig data for the start.twig template
     */
    public function getAllSessions(\model\TrainingSessionsList $trainingSessionList) {
        $this->twigArray[self::PASS] = $trainingSessionList->toTwigArray();
        return $this->twigArray;
    }

    /**
     * Function for getting data to the new session view
     * @param ExercisesList $exercisesList
     * @return array with twig data for session the .twig template
     */
    public function getNewSession(\model\ExercisesList $exercisesList) {
        $this->twigArray[self::EXERCISES] = $exercisesList->toTwigArray();
        return $this->twigArray;
    }

    /**
     * Function for getting data to the session notes view
     * @param SessionExercisesList $sessionExercisesList
     * @return array with twig data for the sessionnotes.twig template
     */
    public function getSessionNotes(\model\SessionExercisesList $sessionExercisesList) {
        $this->twigArray[self::EXERCISES] = $sessionExercisesList->toTwigArray();
        return $this->twigArray;
    }

    /**
     * Function that gets the updated dog array after change of dog
     * Updating the existing twig array
     */
    public function updateDogs() {
        $this->twigArray[self::DOGS] = $this->trainer->getDogs()->toTwigArray();
    }
}