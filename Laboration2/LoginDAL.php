<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:22
 */

class LoginDAL {

    private $host = "xxx"; //"localhost" or "http://mysql.host.com"
    private $user = "xxx"; //an authorized user of the MySQL database
    private $password = "xxx"; //my_username's password
    private $database = "xxx"; //the database we want to use.

    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new \mysqli($this->host, $this->user, $this->password, $this->database);
    }
    // Funktion för att se om användaren finns i databasen
    public function getUserCredentialsFromDB($user, $pwd) {

        $stmt = $this->dbConnection->stmt_init();
        if ($stmt->prepare("SELECT username
                              , password
                              FROM users
                              WHERE username = ? AND password = ?")) {
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
}