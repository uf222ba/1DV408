<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;

require_once("LoginDAL.php");
require_once("Session.php");

class LoginModel extends DAL {
    private $userName;
    private $password;
    private $isUserAuthenticated;
    private $saveLogin;
    private $loginMessage;
    private $DALObject;
    private $calculatedUniqueKey;
    private $userLoginValue;
    private $cookieLogin;
    private $firstName;
    private $lastName;
    private $userId;
    private $dogId;
    private $dogName;

    public function __construct() {
        $this->userName = null;
        $this->password = null;
        $this->loginMessage = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->DALObject = new LoginDAL();
        $this->calculatedUniqueKey = null;
        $this->userLoginValue;
        $this->firstName = null;
        $this->lastName = null;
        $this->userId = null;
        $this->cookieLogin = false;
        $this->dogId = false;
        $this->dogName = false;
    }
    // Funktion för att autentisera användaren
    // Returnerar true om användaren är autentiserad och false om användaruppgifterna är felaktiga
    public function authenticateUser($userName, $password, $clientBrowser) {
        if(empty($userName)) {              // Kollar om användarnamnet är tomt
            $this->loginMessage = 0;        // Sätter vilket meddelande som ska visas i vyn
            return false;
        } elseif(empty($password)) {        // Om lösenordet är tomt
            $this->loginMessage = 1;        // Sätter vilket meddelande som ska visas i vyn
            $this->userLoginValue = $userName; // Det inmatade användarnamnet skrivs ut i textboxen i vyn
            return false;
        } elseif(($userName && $password) !== null) {   // Om både användarnamn och lösenord innehåller något
            if($this->cookieLogin == true) {
                $ret = $this->DALObject->getUserCredentialsWithCookies($userName, $password, $clientBrowser);    // Kolla om uppgifterna finns i databasen
                $result = $this->DALObject->getResultFromQuery();
            } else {
                $ret = $this->DALObject->getUserCredentialsFromDB($userName, $password);    // Kolla om uppgifterna finns i databasen
                $result = $this->DALObject->getResultFromQuery();
            }
            if($ret == true) {                                                          // Uppgifterna var korrekta
                $this->setUserCredentials($userName, $password);                        // Spara användaruppgifterna och användaren är autentiserad
                $this->setSessionVariables();                                           // Spara användarnamnet i sessionsvariabeln
                $this->calculateSessionUniqueKey($clientBrowser);                       // Räkna ut ett unikt värde för sessionen
                $this->firstName = $result['0']['firstname'];
                Session::setSessionVar("firstName", $this->firstName);
                $this->lastName = $result['0']['lastname'];
                Session::setSessionVar("lastName", $this->lastName);
                Session::setSessionVar("userFullName", $result['0']['firstname'] . " " . $result['0']['lastname']);
                $this->userId = $result['0']['user_id'];
                Session::setSessionVar("userId", $this->userId);
                $this->setSessionUniqueKey($this->calculatedUniqueKey);                 // Tilldela variabeln det unika värdet
// Kolla om hundnamnet finns i sessionen - annars hämta från databasen
                if(!(Session::getSessionVar("dogName") && Session::getSessionVar("dogId"))) {
                    if($this->DALObject->getDogsFromDB($this->userId)) {
                        $resultDogs = $this->DALObject->getResultFromQuery();
                        $this->dogId = $resultDogs['0']['hundid'];
                        $this->dogName = $resultDogs['0']['hundnamn'];
                        Session::setSessionVar("dogName", $this->dogName);
                        Session::setSessionVar("dogId", $this->dogId);
                    }
                } else {
                    $this->dogName = Session::getSessionVar("dogName");
                    $this->dogId = Session::getSessionVar("dogId");
                }

                $this->loginMessage = 4;                                                // Sätter vilket meddelande som ska visas i vyn
                return true;
            } else {
                $this->isUserAuthenticated = false; // Användaren är inte autentiserad
                $this->loginMessage = 2;            // Sätter vilket meddelande som ska visas i vyn
                $this->userLoginValue = $userName;  // Det inmatade användarnamnet skrivs ut i textboxen i vyn
                return false;
            }
        }
        else {
            $this->loginMessage = 2;        // Sätter vilket meddelande som ska visas i vyn
            return false;
        }
    }

    // Funktion för ta bort användaruppgifterna i instansvariablerna
    public function logOutUser() {
        $this->loginMessage = 3;    // Sätter vilket meddelande som ska visas i login-vyn
        $this->userName = "";
        $this->password = "";
        $this->firstName = "";
        $this->lastName = "";
        $this->userId = "";
    }

    // Funktion för att hämta värde för meddelandet som ska visas i vyerna
    public function getLoginMessage(){
        return $this->loginMessage;
    }

    // Funktion för att returnera värdet av om användaren är autentiserad eller inte
    // Returnerar true eller false
    public function getIsUserAuthenticated() {
        return $this->isUserAuthenticated;
    }
    // Funktion som hämtar användarnamnet endera från sessionen eller från instansvariabeln
    // Returnerar en sträng
    public function getUserName() {
        if(strlen($this->userName) == 0 && isset($_SESSION["username"]))
            $this->userName = $_SESSION["username"];
        return $this->userName;
    }
    // Funktion för att hämta ut lösenordet
    // Returnerar en sträng
    public function getPassword() {
        return $this->password;
    }
    // Funktion för att sätta användaruppgifterna och att användaren är autentiserad
    public function setUserCredentials($userName, $password) {
        $this->isUserAuthenticated = true;
        $this->userName = $userName;
        $this->password = $password;
    }
    //Funktion för att sätta instansvariabeln för om användaren ska sparas eller inte
    public function setSaveLogin($bool, $clientBrowser) {
        $this->saveLogin = $bool;
        if($this->saveLogin === true) {
            $this->DALObject->setUserCookies($this->userId, $clientBrowser);
            $this->loginMessage = 5;    // Om användaren sparas, så ska meddelande 5 visas
         } elseif($this->saveLogin === false) {
            $this->loginMessage = 4;    // Om användaren inte sparas, så ska meddelande 4 visas
        }
    }
    // Funktion för att hämta instansvariabeln för om användaren sparats eller inte
    // Returnerar true eller false
    public function getSaveLogin() {
        return $this->saveLogin;
    }
    // Funktion för att sätta instansvariabel med vilket nummer på meddelande som ska visas
    public function setLoginMessage($code){
        $this->loginMessage = $code;
    }
    // Funktion för att sätta den unika nyckeln i sessionsvariabeln
    public function setSessionUniqueKey($uniqueKey) {
        $_SESSION["uniqueKey"] = $this->calculatedUniqueKey;
    //    $_SESSION["firstName"] = $this->firstName;
    //    $_SESSION["lastName"] = $this->lastName;
    //    $_SESSION["userId"] = $this->userId;
    }
    // Funktion för att beräkna den unika nyckeln som sätts i sessionsvariabeln och används som tillfälligt lösenord
    public function calculateSessionUniqueKey($clientBrowser) {
        $string = $clientBrowser;
        $string .= "nalta_hittepa"; // Norrländska :)
        $this->calculatedUniqueKey = md5($string);
    }
    // Funktion som returnerar instansvariabeln för den unika nyckeln
    // Returnerar en sträng
    public function getCalculatedUniqueKey() {
        return $this->calculatedUniqueKey;
    }
    // Funktion för att hämta den unika nyckeln som finns i sessionen
    // Returnerar en sträng
    public function getSessionUniqueKey() {
        $uniqueKey = $_SESSION["uniqueKey"];
        return $uniqueKey;
    }
    // Funktion för att kolla om den unika nyckeln i sessionen är densamma som nyckeln efter en reload av webbläsaren
    // Returnerar true eller false
    public function isSessionUniqueKeyValid() {
       if($this->getSessionUniqueKey() == $this->getCalculatedUniqueKey()) {
           $this->setUserInformation();
           return true;
       }
       return false;
    }
    // Funktion för att ta bort sessionsvariabel
    public function deleteSessionVariables() {
        if(isset($_SESSION["uniqueKey"]))
            unset($_SESSION["uniqueKey"]);
    }
    // Funktion för att sätta sessionsvariabeln med användarnamn
    public function setSessionVariables() {
        $_SESSION["username"] = $this->userName;
    }
    // Funktion för att hasha det inmatade lösenordet
    // Returnerar en sträng
    public function setPasswordHash($password){
        if(strlen($password) > 0)
            $hashedPassword = crypt($password, "Yw#jq");
        return $hashedPassword;
    }
    // Funktion för att hämta strängen med det inmatade användarnamnet i login-vyn
    // Returnerar en sträng
    public function getUserNameLoginValue() {
        return $this->userLoginValue;
    }

    // Funktion för att returnera en array med användarinformation
    public function getUserInformation() {
        $userInfo = array("userFullName" => $this->firstName . " " . $this->lastName,
                          "userId" => $this->userId,
                          "dogId" => $this->dogId,
                          "dogName" => $this->dogName);
        return $userInfo;
    }

    // Funktion för att hämta sessionsvariabler och sätta medlemsvariabler
    public function setUserInformation() {
        $this->firstName = Session::getSessionVar("firstName");
        $this->lastName = Session::getSessionVar("lastName");
        $this->userId = Session::getSessionVar("userId");
        $this->dogId = Session::getSessionVar("dogId");
        $this->dogName = Session::getSessionVar("dogName");
    }
}