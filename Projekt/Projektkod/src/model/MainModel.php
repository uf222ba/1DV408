<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;

require_once("ApplicationDAL.php");

class MainModel {

    private $appDal;
    private $name = null;
    private $userId = null;
    private $dogId = null;
    private $dogName = null;

    private static $userIdString = "userId";
    private static $userFullNameString = "userFullName";
    private static $dogIdString = "dogId";
    private static $dogNameString = "dogName";
    private static $passString = "pass";
    private static $passidString = "passid";
    private static $dateString = "date";
    private static $locationString = "location";
    private static $exercisesString = "exercises";
    private static $errorsessionString = "errorsession";
    private static $errorlocationString = "errorlocation";
    private static $errordateString = "errordate";

    public function __construct() {
        $this->appDal = new ApplicationDAL();
    }

    public function setUserInfo($userInformation) {
        $this->userId = $userInformation[self::$userIdString];
        $this->name =$userInformation[self::$userFullNameString];
        $this->dogId = $userInformation[self::$dogIdString];
        $this->dogName = $userInformation[self::$dogNameString];
    }

    public function getName() {
        return $this->name;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function start() {
        return array(self::$userFullNameString => $this->name, self::$dogNameString => $this->dogName, self::$passString => $this->appDal->getTrainingSessionsFromDB($this->userId, $this->dogId));
    }

    public function getNewSessionExercises() {
        return $this->appDal->getAllExercises();
    }

    public function newSession($parameters) //$parameters är en dummy
    {
        return array(self::$userFullNameString => $this->name, self::$dogNameString => $this->dogName, self::$exercisesString => $this->getNewSessionExercises());
    }

    public function newSessionNotes($parameters)
    {
        $lastInsertId = $this->appDal->addNewSession($parameters[self::$dateString], $parameters[self::$locationString], $parameters[self::$exercisesString], $this->userId, $this->dogId);
        return array(self::$userFullNameString => $this->name, self::$dogNameString => $this->dogName, self::$exercisesString => $this->appDal->getSessionExercises($lastInsertId));
    }

    public function updateSessionNotes($parameters)
    {
        return array(self::$userFullNameString => $this->name, self::$dogNameString => $this->dogName, self::$exercisesString => $this->appDal->getSessionExercises($parameters[self::$passidString]));
    }

    public function updateStart($parameters)
    {
        $this->appDal->updateTrainingSession($parameters);
        return $this->start();
    }

    public function deleteStart($parameters) {
        $this->appDal->deleteTrainingSession($parameters);
        return $this->start();
    }
}