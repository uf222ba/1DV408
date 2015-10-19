<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("DAL.php");
require_once("src/model/TrainingSessionsList.php");
require_once("src/model/TrainingSession.php");
require_once('src/model/ExercisesList.php');
require_once('src/model/Exercise.php');
require_once('src/model/SessionExercisesList.php');

/**
 * Class ApplicationDAL inherits the DAL Class
 * Class with functions for getting data from the database
 * and adding data to the database
 * @package model
 */

class ApplicationDAL extends DAL{

    private $result;
    private $trainer;

    /**
     * Constructor
     * @param Trainer $trainerObj
     */
    public function __construct(Trainer $trainerObj) {
        $this->result = null;
        $this->trainer = $trainerObj;
    }

    /**
     * Function for getting trainingsessions from the database
     * and adding them to a TraingSessionList object
     * @return TrainingSessionsList|null
     */
    public function getTrainingSessions() {
        $trainingSessionList = new TrainingSessionsList();

        $sql = "SELECT traningspass.traningspassid
                    , traningspass.traningspassdatum
                    , traningspass.traningspassplats
                FROM " . \config\LocalSettings::DB_NAME . ".traningspass
                WHERE traningspass.hundid_fk = :dogid AND traningspass.userid_fk = :userid
                ORDER BY traningspass.traningspassdatum desc;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(":userid" => $this->trainer->getUserId(),
            ":dogid" => $this->trainer->getDefaultDogId() ));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){

            foreach($this->result as $session) {
                $s = new TrainingSession();
                $s->setId($session['traningspassid']);
                $s->setDate($session['traningspassdatum']);
                $s->setLocation($session['traningspassplats']);

                $trainingSessionList->add($s);
            }
            return $trainingSessionList;
        }
        return null;
    }

    /**
     * Function for getting all exercises from the database
     * and adding them to a ExerciseList object
     * @return ExercisesList|null
     */
    public function getAllExercises() {
        $sql = "SELECT moment.momentid
                , moment.momentnamn
                FROM " . \config\LocalSettings::DB_NAME . ".moment;";

        $pdoStmt = $this->connection()->query($sql);
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        $exerciseList = new ExercisesList();

        if(count($this->result) > 0){
            foreach($this->result as $exercise) {
                $e = new Exercise();
                $e->setId($exercise['momentid']);
                $e->setName($exercise['momentnamn']);

                $exerciseList->add($e);
            }
            return $exerciseList;
        }
        return null;
    }

    /**
     * Function for getting all the saved exercises that belongs to a specific
     * training session and add them to a SessionExerciseList object
     * @param $passId
     * @return SessionExercisesList|null
     */
    public function getSessionExercises($passId) {

            $sql = "SELECT traningspass.traningspassid
                        , traningspass.traningspassdatum
                        , traningspass.traningspassplats
                        , traningspass.anteckningar
                        , passdetaljer.passdetaljerid
                        , passdetaljer.repetitioner
                        , passdetaljer.lyckaderepetitioner
                        , passdetaljer.storning
                        , passdetaljer.kommentarer
                        , moment.momentnamn
                    FROM " . \config\LocalSettings::DB_NAME . ".passdetaljer
                        INNER JOIN " . \config\LocalSettings::DB_NAME . ".moment ON passdetaljer.momentid_fk = moment.momentid
                        INNER JOIN " . \config\LocalSettings::DB_NAME . ".traningspass ON traningspass.traningspassid = passdetaljer.traningspassid_fk
                    WHERE passdetaljer.traningspassid_fk = :passid;";

            $pdoStmt = $this->connection()->prepare($sql);
            $pdoStmt->execute(array(":passid" => $passId));
            $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
            $this->result = $pdoStmt->fetchAll();

            $sessionExerciseList = new SessionExercisesList();

            if(count($this->result) > 0) {
                foreach($this->result as $exercise) {
                    $e = new TrainingSession();
                    $e->setId($exercise['traningspassid']);
                    $e->setDate($exercise['traningspassdatum']);
                    $e->setLocation($exercise['traningspassplats']);
                    $e->setNotes($exercise['anteckningar']);
                    $e->setExerciseId($exercise['passdetaljerid']);
                    $e->setRepetitions($exercise['repetitioner']);
                    $e->setSucceededReps($exercise['lyckaderepetitioner']);
                    $e->setEnvironment($exercise['storning']);
                    $e->setComments($exercise['kommentarer']);
                    $e->setExerciseName($exercise['momentnamn']);

                    $sessionExerciseList->add($e);
                }
                return $sessionExerciseList;
            }
        return null;
    }

    /**
     * Function for adding a new training session to the database
     * @param $date string with date and eventually time yyyy-mm-dd hh:mm
     * @param $location place where the training i located
     * @param $exercises an array of the selected exercises
     * @return string that is the id of the added training session
     */
    public function addNewSession($date, $location, $exercises) {
        $sql = "INSERT INTO " . \config\LocalSettings::DB_NAME . ".traningspass (traningspassdatum, traningspassplats,userid_fk, hundid_fk)
                VALUES (:vardate, :location, :userid, :dogid);";

        $pdoStmt = $this->connection()->prepare($sql);

        $pdoStmt->execute(array(":vardate" => $date,
            ":location" => $location,
            ":userid" => $this->trainer->getUserId(),
            ":dogid" => $this->trainer->getDefaultDogId()));

        $lastInsertID = $this->connection()->lastInsertId();
        $this->addNewSessionExercises($lastInsertID, $exercises);
        return $lastInsertID;
    }

    /**
     * Function for adding new exercises to a training session
     * @param $sessionId
     * @param $exercises
     */
    public function addNewSessionExercises($sessionId, $exercises) {
        $sql = "INSERT INTO " . \config\LocalSettings::DB_NAME . ".passdetaljer (traningspassid_fk, momentid_fk)
                VALUES (:sessionid, :momentid);";

        $pdoStmt = $this->connection()->prepare($sql);

        foreach($exercises as $e) {
            $pdoStmt->execute(array(":sessionid" => $sessionId,
                ":momentid" => $e ));
        }
    }

    /**
     * Function for updating a training session with data like
     * notes, repetitions, succeeded repetitions
     * @param $sessionId
     * @param $sessionNotes
     * @param $exercises
     */
    public function saveTrainingSessionNotes($sessionId, $sessionNotes, $exercises) {

        $sql = "UPDATE " . \config\LocalSettings::DB_NAME . ".traningspass
                SET anteckningar = :sessionnotes
                WHERE traningspassid = :passid;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(":sessionnotes" => $sessionNotes,
            ":passid" => $sessionId ));

        /**
         * Sparar momentspecifika data:
         *                          Momentspecifika anteckningar
         *                          Totalt antal repetitioner
         *                          Lyckade repetitioner
         */

        foreach($exercises as $key => $value) {
            $repetitions = $value[1];
            $succeededReps = $value[2];

            if(strlen($repetitions) < 1 && strlen($succeededReps) < 1){
                $sql ="UPDATE " . \config\LocalSettings::DB_NAME . ".passdetaljer
                       SET kommentarer = :comments
                       WHERE passdetaljerid = :passdetaljerid;";

                $pdoStmt = $this->connection()->prepare($sql);
                $pdoStmt->execute(array(":comments" => $value[0],
                                        ":passdetaljerid" => $key));
            } elseif(strlen($repetitions) < 1) {
                $sql ="UPDATE " . \config\LocalSettings::DB_NAME . ".passdetaljer
                       SET lyckaderepetitioner = :succeeded,
                           kommentarer = :comments
                       WHERE passdetaljerid = :passdetaljerid;";

                $pdoStmt = $this->connection()->prepare($sql);
                $pdoStmt->execute(array(":succeeded" => intval($value[2]),
                                        ":comments" => $value[0],
                                        ":passdetaljerid" => $key));
            } elseif(strlen($succeededReps) < 1) {
                $sql ="UPDATE " . \config\LocalSettings::DB_NAME . ".passdetaljer
                       SET repetitioner = :repetitions,
                           kommentarer = :comments
                       WHERE passdetaljerid = :passdetaljerid;";

                $pdoStmt = $this->connection()->prepare($sql);
                $pdoStmt->execute(array(":repetitions" => intval($value[1]),
                                        ":comments" => $value[0],
                                        ":passdetaljerid" => $key));
            } else {
                $sql ="UPDATE " . \config\LocalSettings::DB_NAME . ".passdetaljer
                   SET repetitioner = :repetitions,
                       lyckaderepetitioner = :succeeded,
                       kommentarer = :comments
                   WHERE passdetaljerid = :passdetaljerid;";

                $pdoStmt = $this->connection()->prepare($sql);
                $pdoStmt->execute(array(":repetitions" => intval($value[1]),
                                        ":succeeded" => intval($value[2]),
                                        ":comments" => $value[0],
                                        ":passdetaljerid" => $key));
            }
        }
    }

    /**
     * Function for deleting a training session
     * @param $sessionId
     */
    public function deleteTrainingSession($sessionId) {
        // DELETE from traningspass where passid = ...
        /* Traningspass */
        $sql = "DELETE FROM " . \config\LocalSettings::DB_NAME . ".traningspass
                WHERE traningspassid = :passid;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(":passid" => $sessionId));

        // DELETE from passdetaljer where passid_fk = passid
        /* passdetaljer */
        $sql = "DELETE FROM " . \config\LocalSettings::DB_NAME . ".passdetaljer
                WHERE traningspassid_fk = :passid;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(":passid" => $sessionId));
    }
}


