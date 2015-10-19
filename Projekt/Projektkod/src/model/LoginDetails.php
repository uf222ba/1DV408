<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class LoginDetails
 * Class for storing login data details
 * @package model
 */
class LoginDetails
{
    private $hashedPassword;
    private $isUserAuthenticated;
    private $saveLogin;
    private $loginMessage;
    private $cookieLogin;
    private $calculatedUniqueKey;
    private $sessionUniqueKey;
    private $clientBrowser;
    private $clientIpAddress;

    /**
     * Constructor that tries to get the uniqueKey from the session upon creation
     */
    public function __construct() {
        $this->loginMessage = null;
        $this->userLoginValue = null;
        $this->cookieLogin = false;
        $this->calculatedUniqueKey = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->hashedPassword = null;
        $this->clientBrowser = null;
        $this->sessionUniqueKey = Session::getSessionUniqueKey();
    }

    /**
     * Getters and setters for the instance variables
     */

    public function getHashedPassword() {
        return $this->hashedPassword;
    }

    public function getIsUserAuthenticated() {
        return $this->isUserAuthenticated;
    }

    public function getSaveLogin() {
        return $this->saveLogin;
    }

    public function getLoginMessage() {
        return $this->loginMessage;
    }

    public function getCookieLogin() {
        return $this->cookieLogin;
    }
    public function getCalculatedUniqueKey() {
        return $this->calculatedUniqueKey;
    }

    public function getSessionUniqueKey() {
        return $this->sessionUniqueKey;
    }

    public function getClientBrowser() {
        return $this->clientBrowser;
    }

    public function getClientIpAddress() {
        return $this->clientIpAddress;
    }

    public function setHashedPassword($hashedPassword) {
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * Function for hashing the text from the password text box
     * @param $password
     */
    public function setPasswordHash($password){
        if(strlen(trim($password)) > 0)
            $this->hashedPassword = crypt($password, "Yw#jq");
    }

    public function setIsUserAuthenticated($isUserAuthenticated) {
        $this->isUserAuthenticated = $isUserAuthenticated;
    }
    public function setSaveLogin($saveLogin) {
        $this->saveLogin = $saveLogin;
    }
    public function setLoginMessage($loginMessage) {
        $this->loginMessage = $loginMessage;
    }

    public function setCookieLogin($cookieLogin) {
        $this->cookieLogin = $cookieLogin;
    }
    public function setCalculatedUniqueKey($calculatedUniqueKey) {
        $this->calculatedUniqueKey = $calculatedUniqueKey;
    }

    public function setSessionUniqueKey($sessionUniqueKey) {
        $this->sessionUniqueKey = $sessionUniqueKey;
    }

    public function setClientBrowser($clientBrowser) {
        $this->clientBrowser = $clientBrowser;
    }

    public function setClientIpAddress($clientIpAddress) {
        $this->clientIpAddress = $clientIpAddress;
    }

    /**
     * Function for setting the clientBrowser and the clientIpAdress
     * instance variables and calculating the uniqueKey
     * @param $clientBrowser
     * @param $clientIpAddress
     */
    public function setClientData($clientBrowser, $clientIpAddress) {
        $this->clientBrowser = $clientBrowser;
        $this->clientIpAddress = $clientIpAddress;
        $this->calculateUniqueKey();
    }

    /**
     *  Function for creating the uniqueKey
     */
    public function calculateUniqueKey() {
        $string = $this->clientBrowser . $this->clientIpAddress . "nalta_hittepa"; // Norrländska :)
        $this->calculatedUniqueKey = md5($string);
    }

    /**
     * Function for deleting all instance variables stored information
     */
    public function deleteLoginDetails() {
        $this->cookieLogin = false;
        $this->calculatedUniqueKey = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->hashedPassword = null;
        $this->clientBrowser = null;
        $this->sessionUniqueKey = null;
        $this->clientIpAddress = null;
    }
}