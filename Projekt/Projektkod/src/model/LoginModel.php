<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("src/model/DAL/LoginDAL.php");
require_once("LoginDetails.php");
require_once("Session.php");
require_once("User.php");

/**
 * Class LoginModel
 * Class for handling login and logout requests
 * @package model
 */
class LoginModel
{
    private $user;
    private $login;
    private $loginDAL;

    public function __construct() {
        $this->user = new User();
        $this->login = new LoginDetails();
        $this->loginDAL = new LoginDAL($this->user, $this->login);
    }

    /**
     * Getters for instance variables
     */

    public function getUser() {
        return $this->user;
    }

    public function getLoginDetails() {
        return $this->login;
    }

    public function getLoginDAL() {
        return $this->loginDAL;
    }

    /**
     * Function for authentication of user
     * @param $userName
     * @param $password
     * @return bool
     */
    public function authenticateUser($userName, $password)
    {
        if (empty($userName)) {                         // Kollar om användarnamnet är tomt
            $this->login->setLoginMessage(0);
            return false;
        } elseif (empty($password)) {                   // Om lösenordet är tomt $password()
            $this->login->setLoginMessage(1);
            $this->user->setUsername($userName);        // Det inmatade användarnamnet skrivs ut i textboxen i vyn
            return false;
        } elseif (($userName && $password) != null) {   // Om både användarnamn och lösenord innehåller något
            $this->user->setUsername($userName);
            if ($this->login->getCookieLogin() == true) {
                $ret = $this->loginDAL->getUserCredentialsWithCookies($userName, $password);    // Kolla om uppgifterna finns i databasen
            } else {
                $ret = $this->loginDAL->getUserCredentialsFromDB($userName, $password);    // Kolla om uppgifterna finns i databasen
            }
            if ($ret == true) {                                                          // Uppgifterna var korrekta
                $this->setUserLoginApproved();
                $this->login->setLoginMessage(4);
                return true;
            } else {
                $this->login->setIsUserAuthenticated(false); // Användaren är inte autentiserad
                $this->login->setLoginMessage(2);
                return false;
            }
        } else {
            $this->login->setLoginMessage(2);
            return false;
        }
    }

    /**
     *  Function for logging out the user
     */
    public function logOutUser()
    {
        $this->login->setLoginMessage(3);    // Sätter vilket meddelande som ska visas i login-vyn
        $this->user->deleteUserCredentials();
        $this->login->deleteLoginDetails();
        Session::deleteSessionVariables();
    }

    /**
     * Function for checking that the unique key in the session i contains the same value as the in the database
     * @return bool true if it's the same value, otherwise false
     */
    public function isSessionUniqueKeyValid()
    {
        if ($this->login->getSessionUniqueKey() == $this->login->getCalculatedUniqueKey()) {
            Session::setUserInformationFromSession($this->user);
            Session::setSessionUniqueKey($this->login);
            return true;
        }
        return false;
    }

    /**
     * Private function for setting the session variables when user has been authenticated
     */
    private function setUserLoginApproved() {
        Session::setSessionVariablesFromUser($this->user);
        Session::setSessionUniqueKey($this->login);
    }

    /**
     * Function for saving the login with cookies
     */
    public function saveCookieLogin() {
        $this->login->setSaveLogin(true);
        $this->loginDAL->setUserCookies($this->user->getUserId(), $this->login->getClientBrowser());
        $this->login->setLoginMessage(5);
    }
}