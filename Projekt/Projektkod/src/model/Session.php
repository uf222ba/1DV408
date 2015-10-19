<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class Session
 * Wrapper class for handling session variables
 * Containing constants and static functions
 * @package model
 */
class Session {

    const FIRSTNAME = "firstName";
    const LASTNAME = "lastName";
    const USERFULLNAME = "userFullName";
    const USERNAME = "username";
    const USERID = "userId";
    const UNIQUEKEY = "uniqueKey";

    /**
     * Function for setting the session array with name and userId
     * @param User $userObject
     */
    static function setSessionVariablesFromUser(User $userObject) {
        Session::setSessionVar(self::FIRSTNAME, $userObject->getFirstName());
        Session::setSessionVar(self::LASTNAME, $userObject->getLastName());
        Session::setSessionVar(self::USERFULLNAME, $userObject->getFirstName() . " " . $userObject->getLastName());
        Session::setSessionVar(self::USERID, $userObject->getUserId());
    }

    /**
     * Function for setting the instance variables in the user object
     * with data from the session array
     * @param User $userObject
     */
    static function setUserInformationFromSession(User $userObject) {
        $userObject->setFirstName(Session::getSessionVar(self::FIRSTNAME));
        $userObject->setLastName(Session::getSessionVar(self::LASTNAME));
        $userObject->setUserId(Session::getSessionVar(self::USERID));
    }

    /**
     * Function for setting the unique key in the session array
     * @param LoginDetails $loginObject
     */
    static function setSessionUniqueKey(LoginDetails $loginObject) {
        $_SESSION[self::UNIQUEKEY] = $loginObject->getCalculatedUniqueKey();
    }

    /**
     * Function for getting the unique key from the session array
     * @return string containg the unique key
     */
    static function getSessionUniqueKey() {
        return self::getSessionVar(self::UNIQUEKEY);
    }

    /**
     * Function for getting the userName from the session array
     * @return string userName
     */
    static function getUserName() {
        if(isset($_SESSION[self::USERNAME]))
            $userName = $_SESSION[self::USERNAME];
        else
            $userName = null;
        return $userName;
    }

    /**
     * Function for setting the userName in the session array
     * @param $userName
     */
    static function setUserName($userName) {
        Session::setSessionVar(self::USERNAME, $userName);
    }

    /**
     * Function for unsetting all session variables in the session array
     */
    static function deleteSessionVariables() {
        session_unset();
    }

    /**
     * Wrapper function for setting a session array value
     * @param $key
     * @param $value
     */
    static function setSessionVar($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Wrapper function for getting a value from the session array
     * @param $key
     * @return bool
     */
    static function getSessionVar($key) {
        if(isset($_SESSION[$key]))
            return $_SESSION[$key];
        return false;
    }
}
