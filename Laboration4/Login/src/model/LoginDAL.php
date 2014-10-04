<?php

namespace model;

class LoginDAL {

    private $host = "localhost"; //"localhost" or "http://mysql.host.com"
    private $user = "root"; //an authorized user of the MySQL database
    private $password = ""; //my_username's password
    private $database = "php_db"; //the database we want to use.

    private $dbConnection;

    public function __construct() {
        $this->dbConnection = new \mysqli($this->host, $this->user, $this->password, $this->database);
    }

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