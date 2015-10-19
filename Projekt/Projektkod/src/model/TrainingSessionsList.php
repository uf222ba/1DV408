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
 * Class TrainingSessionsList
 * Inherits the base class ModelList
 * List for storing a TrainingSession Class objects
 * This class is used for getting all sessions for a user and the dog of choice.
 * It is used in the start.twig template
 * @package model
 */
class TrainingSessionsList extends ModelList {

    /**
     * Function for creating and returning an array that suits de Twig demands.
     * @return array the TrainingSessionsList as an associative array
     */
    public function toTwigArray() {
        $arrOfSessions = array();

        foreach($this->theList as $session) {
            $arrOfSessions[] = array('traningspassid' => $session->getId(),
                                     'traningspassdatum' => $session->getDate(),
                                     'traningspassplats' => $session->getLocation());
        }
        return $arrOfSessions;
    }
}