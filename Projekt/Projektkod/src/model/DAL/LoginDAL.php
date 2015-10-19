<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("DAL.php");
require_once("src/model/User.php");
require_once("src/model/LoginDetails.php");

/**
 * Class LoginDAL inherits the DAL class
 * Class with functions for handling data exchange with the database
 * concerning the login part of the application
 * @package model
 */
class LoginDAL extends DAL{

    private $dbConnection;
    private $result;
    private $user;
    private $login;

    /**
     * Constructor
     * @param User $user
     * @param LoginDetails $login
     */
    public function __construct(User $user, LoginDetails $login) {
        $this->dbConnection = $this->connection();
        $this->result = null;
        $this->user = $user;
        $this->login = $login;
    }

    /**
     * Function for checking if the user is in the database
     * @param $user
     * @param $pwd
     * @return bool returns true if user and password is ok, otherwise false
     */
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
            $this->user->setUsername($this->result['0']['username']);
            $this->user->setPassword($this->result['0']['password']);
            $this->user->setUserId($this->result['0']['user_id']);
            $this->user->setFirstName($this->result['0']['firstname']);
            $this->user->setLastName($this->result['0']['lastname']);

            $this->login->setIsUserAuthenticated(true);
/*
            var_dump($this->result);
            echo("<br /><br />");
            var_dump($this->user);
            die(); */
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Function for authenticate a user with cookies
     * @param $user
     * @param $pwd
     * @return bool
     */
    public function getUserCredentialsWithCookies($user, $pwd) {
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
                                ":clientbrowser" => $this->login->getClientBrowser()));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) == 1){
            if($this->checkCookie($this->result['0']['cookietimestamp'])) {
                $this->user->setUsername($this->result['0']['username']);
                $this->user->setPassword($this->result['0']['password']);
                $this->user->setUserId($this->result['0']['user_id']);
                $this->user->setFirstName($this->result['0']['firstname']);
                $this->user->setLastName($this->result['0']['lastname']);

                $this->login->setIsUserAuthenticated(true);
                return true;
            }
            else
                return false;
        }
        else {
            return false;
        }
    }

    /**
     * Function for checking that the cookie is ok
     * @param $cookietimestamp
     * @return bool
     */
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

    /**
     * Function for checking if the user exists in the database
     * @param $user
     * @return bool
     */
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

    /**
     * Function for saving cookie data to database
     * @param $userId
     * @param $clientBrowser
     */
    public function setUserCookies($userId, $clientBrowser) {
        $sql = 'UPDATE falkebo_com.users
                SET cookietimestamp = UNIX_TIMESTAMP(),
                    browser = :clientbrowser
                WHERE user_id = :userid;';

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":clientbrowser" => $clientBrowser,
                                ":userid" => $userId ));
    }
}