<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;

require_once("DAL.php");

class ApplicationDAL extends DAL{

    private $result;

    private static $useridString = ":userid";
    private static $dogidString = ":dogid";
    private static $passidString = ":passid";
    private static $vardateString = ":vardate";
    private static $sessionidString = ":sessionid";
    private static $locationString = ":location";
    private static $momentidString = ":momentid";
    private static $sessionnotesColonString = ":sessionnotes";
    private static $repetitionsString = ":repetitions";
    private static $succeededString = ":succeeded";
    private static $commentsString = ":comments";
    private static $passdetaljeridString = ":passdetaljerid";

    private static $updateDataString = "updateData";
    private static $sessionnotesString = "sessionnotes";
    private static $exerciseString = "exercise";

    public function __construct() {
        $this->result = null;
    }

    public function getTrainingSessionsFromDB($userId, $dogId) {

        $sql = "SELECT traningspass.traningspassid
                    , traningspass.traningspassdatum
                    , traningspass.traningspassplats
                FROM php_db.traningspass
                WHERE traningspass.hundid_fk = :dogid AND traningspass.userid_fk = :userid
                ORDER BY traningspass.traningspassdatum desc;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(self::$useridString => $userId,
                                self::$dogidString => $dogId ));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){
            return $this->result;
        }
        else {
            return false;
        }
    }

    public function getAllExercises() {
        $sql = "SELECT moment.momentid
                , moment.momentnamn
                FROM php_db.moment;";

        $pdoStmt = $this->connection()->query($sql);
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){
            return $this->result;
        }
        else {
            return false;
        }
    }

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
                FROM php_db.passdetaljer
                    INNER JOIN php_db.moment ON passdetaljer.momentid_fk = moment.momentid
                    INNER JOIN php_db.traningspass ON traningspass.traningspassid = passdetaljer.traningspassid_fk
                WHERE passdetaljer.traningspassid_fk = :passid;";

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(self::$passidString => $passId));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){
            return $this->result;
        }
        else {
            return false;
        }
    }

    public function addNewSession($date, $location, $exercises, $userId, $dogId) {

        $sql = "INSERT INTO php_db.traningspass (traningspassdatum, traningspassplats,userid_fk, hundid_fk)
                VALUES (:vardate, :location, :userid, :dogid);";

        $pdoStmt = $this->connection()->prepare($sql);

        $pdoStmt->execute(array(self::$vardateString => $date,
                                self::$locationString => $location,
                                self::$useridString => $userId,
                                self::$dogidString => $dogId ));

        $lastInsertID = $this->connection()->lastInsertId();
        $this->addNewSessionExercises($lastInsertID, $exercises);
        return $lastInsertID;
    }

    public function addNewSessionExercises($sessionId, $exercises) {

        $sql = "INSERT INTO php_db.passdetaljer (traningspassid_fk, momentid_fk)
                VALUES (:sessionid, :momentid);";

        $pdoStmt = $this->connection()->prepare($sql);

        foreach($exercises as $e) {
            $pdoStmt->execute(array(self::$sessionidString => $sessionId,
                                    self::$momentidString => $e ));
        }
    }

    public function updateTrainingSession($parameters) {
        // Börja med att nysta upp parametrarna...
        $passid = $parameters[self::$passidString];
        $updateData = $parameters[self::$updateDataString];
        $sessionNotes = $updateData[self::$sessionnotesString];
        $exercises = $updateData[self::$exerciseString];

        /* träningspass */
        $sql = 'UPDATE php_db.traningspass
                SET anteckningar = :sessionnotes
                WHERE traningspassid = :passid;';

            $pdoStmt = $this->connection()->prepare($sql);
            $pdoStmt->execute(array(self::$sessionnotesColonString => $sessionNotes,
                                    self::$passidString => $passid ));

        /* passdetaljer */
        $sql ='UPDATE php_db.passdetaljer
               SET repetitioner = :repetitions,
                   lyckaderepetitioner = :succeeded,
                   kommentarer = :comments
               WHERE passdetaljerid = :passdetaljerid;';

        $pdoStmt = $this->connection()->prepare($sql);

        foreach($exercises as $key => $value) {
            $pdoStmt->execute(array(self::$repetitionsString => intval($value[1]),
                                    self::$succeededString => intval($value[2]),
                                    self::$commentsString => $value[0],
                                    self::$passdetaljeridString => $key));
        }
    }

    public function deleteTrainingSession($parameters) {

        $passid = $parameters[self::$passid];

        // DELETE from traningspass where passid = ...
        /* Traningspass */
        $sql = 'DELETE FROM php_db.traningspass
                WHERE traningspassid = :passid;';

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(self::$passidString => $passid ));

        // DELETE from passdetaljer where passid_fk = passid
        /* passdetaljer */
        $sql = 'DELETE FROM php_db.passdetaljer
                WHERE traningspassid_fk = :passid;';

        $pdoStmt = $this->connection()->prepare($sql);
        $pdoStmt->execute(array(self::$passidString => $passid ));
    }
}


