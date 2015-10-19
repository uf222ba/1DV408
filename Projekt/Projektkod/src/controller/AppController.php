<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace controller;

require_once('src/model/DAL/ApplicationDAL.php');
require_once('src/model/TwigArray.php');
require_once('src/model/Trainer.php');
require_once('src/model/DAL/DogDAL.php');

/**
 * Class AppController
 * Class containing functions for setting and getting the right data and return it.
 * @package controller
 */
class AppController
{
    private $repository;
    private $trainer;
    private $twigArray;

    /**
     * Constructor with dependency injection for a user object
     * It also create instances for access to the data access class
     * and the class responsible for building Twig arrays
     * @param \model\User $userObject
     */
    public function __construct(\model\User $userObject) {
        $this->trainer = new \model\Trainer($userObject);
        $this->repository = new \model\ApplicationDAL($this->trainer);
        $this->twigArray = new \model\TwigArray($this->trainer);
    }

    /**
     * Function for getting training sessions from the application DAL and
     * call the twig array building function.
     * @return array for the start.twig template
     */
    public function showAllSessions()
    {
        return $this->twigArray->getAllSessions($this->repository->getTrainingSessions());
    }

    /**
     * Function for getting all exercises from the application DAL and
     * call the twig array building function.
     * @return array for the session.twig template
     */
    public function addSession()
    {
        return $this->twigArray->getNewSession($this->repository->getAllExercises());
    }

    /**
     * Function for adding new session with the application DAL function and
     * call the twig array building function.
     * @param $date
     * @param $location
     * @param $exercises
     * @return array for the sessionnotes.twig template
     */
    public function addSessionNotes($date, $location, $exercises)
    {
        $lastInsertId = $this->repository->addNewSession($date, $location, $exercises);
        return $this->twigArray->getSessionNotes($this->repository->getSessionExercises($lastInsertId));
    }

    /**
     * Function that calls function in application DAL and gets the exercises from there
     * and calls the twig array builder function
     * @param $sessionId
     * @return array for the sessionnotes.twig template
     */
    public function updateSessionNotes($sessionId)
    {
        return $this->twigArray->getSessionNotes($this->repository->getSessionExercises($sessionId));
    }

    /**
     * Function that calls the application DAL function that saves the data input from
     * the sessionnotes view
     * @param $sessionId
     * @param $sessionNotes
     * @param $exercises
     * @return array for the start.twig template
     */
    public function saveSessionNotes($sessionId, $sessionNotes, $exercises) {
        $this->repository->saveTrainingSessionNotes($sessionId, $sessionNotes, $exercises);
        return $this->showAllSessions();
    }

    /**
     * Function that calls the application DAL function that deletes a training session
     * @param $sessionId
     * @return array for the start.twig template
     */
    public function deleteSession($sessionId)
    {
        $this->repository->deleteTrainingSession($sessionId);
        return $this->showAllSessions();
    }

    /**
     * Function for changing and updating the dog.
     * @param $dogId
     * @return array for the start.twig template
     */
    public function changeDog($dogId) {
        $dogRepository = new \model\DogDAL();
        $dogRepository->updateSelectedDog($this->trainer->getUserId(), $dogId); //Uppdaterar vilken hund som är vald i databasen.
        $this->trainer->updateDogs();   // Hämtar uppdaterade data från databasen om vilken hund som är vald
        $this->twigArray->updateDogs(); // Uppdaterar arrayen som ska till Twig
        return $this->showAllSessions();
    }
}