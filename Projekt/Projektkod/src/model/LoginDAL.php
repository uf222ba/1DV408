<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;

require_once("DAL.php");

class LoginDAL extends DAL{
/*
    private $host = "localhost"; //"localhost" or "http://mysql.host.com"
    private $user = "root"; //an authorized user of the MySQL database
    private $password = "Nisse123"; //my_username's password
    private $database = "php_db"; //the database we want to use.
*/
    private $dbConnection;
    private $result;

    public function __construct() {
        $this->dbConnection = $this->connection();
        //$this->dbConnection = new \mysqli($this->host, $this->user, $this->password, $this->database);
        $this->result = null;
    }
    // Funktion för att se om användaren finns i databasen
    public function getUserCredentialsFromDB($user, $pwd) {

        $sql = "SELECT username
                      , password
                      , firstname
                      , lastname
                      , user_id
                FROM users
                WHERE username = :user AND password = :password";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":user" => $user
                                , ":password" => $pwd));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) == 1){
            return true;
        }
        else {
            return false;
        }
    }

    public function getUserCredentialsWithCookies($user, $pwd, $clientBrowser) {
        $sql = "SELECT username
                      , password
                      , firstname
                      , lastname
                      , user_id
                      , cookietimestamp
                      , browser
                FROM users
                WHERE username = :user AND password = :password AND browser = :clientbrowser";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":user" => $user,
                                ":password" => $pwd,
                                ":clientbrowser" => $clientBrowser));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) == 1){
            if($this->checkCookie($this->result['0']['cookietimestamp']))
                return true;
            else
                return false;
        }
        else {
            return false;
        }
    }

    private function checkCookie($cookietimestamp) {
        $maxtime = 180;

        if($cookietimestamp != null) {
            $difference = time() - $cookietimestamp;
            if($difference < $maxtime)
                return true;
            else
                return false;
        }
        return false;
    }

    // Funktion för att kolla om användaren finns i databasen
    public function isUserInDB($user) {

        $stmt = $this->dbConnection->stmt_init();
        if ($stmt->prepare("SELECT username
                              FROM users
                              WHERE username = ?")) {
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->fetch(); // Hämtar resultatet och returnerar en array
            $stmt->close();
        }

        if(count($result) == 1){
            return true;
        }
        else {
            return false;
        }
    }
    // Funktion för att skapa ny användare
    public function createNewUser($user, $pwd) {

        $stmt = $this->dbConnection->stmt_init();
        if ($stmt->prepare("INSERT INTO users(username, password)
                              VALUES (?, ?)")) {
            $stmt->bind_param("ss", $user, $pwd);
            $stmt->execute();
            $result = $stmt->fetch(); // Hämtar resultatet och returnerar en array
            $stmt->close();
        }

        if(count($result) == 1){
            return true;
        }
        else {
            return false;
        }
    }

    public function isUserInDBTest($user) {

        $stmt = $this->dbConnection->stmt_init();
        if ($stmt->prepare("SELECT username
                              FROM users
                              WHERE username = ?")) {
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->fetch(); // Hämtar resultatet och returnerar en array
            $stmt->close();
        }

        if(count($result) == 1){
            return true;
        }
        else {
            return false;
        }
    }

    public function getResultFromQuery() {
        return $this->result;
    }

    public function getDogsFromDB($userId) {

        $sql = "SELECT hundar.hundid
	                  , hundar.hundnamn
	                  , hundar.profilbild
                FROM php_db.hundar
                WHERE hundar.userid_fk = :userid";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":userid" => $userId));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function setUserCookies($userId, $clientBrowser) {
        $sql = 'UPDATE php_db.users
                SET cookietimestamp = UNIX_TIMESTAMP(),
                    browser = :clientbrowser
                WHERE user_id = :userid;';

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":clientbrowser" => $clientBrowser,
                                ":userid" => $userId ));
    }
}