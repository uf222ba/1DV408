<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:23
 */

require_once("LoginDAL.php");

class LoginModel {
    private $userName;
    private $password;
    private $isUserAuthenticated;
    private $saveLogin;
    private $loginMessage;
    private $DALObject;
    private $calculatedUniqueKey;
    private $userLoginValue;

    public function __construct() {
        $this->userName = null;
        $this->password = null;
        $this->loginMessage = null;
        $this->isUserAuthenticated = false;
        $this->saveLogin = false;
        $this->DALObject = new LoginDAL();
        $this->calculatedUniqueKey = null;
        $this->userLoginValue;
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
            $ret = $this->DALObject->getUserCredentialsFromDB($userName, $password);    // Kolla om uppgifterna finns i databasen
            if($ret == true) {                                                          // Uppgifterna var korrekta
                $this->setUserCredentials($userName, $password);                        // Spara användaruppgifterna och användaren är autentiserad
                $this->setSessionVariables();                                           // Spara användarnamnet i sessionsvariabeln
                $this->calculateSessionUniqueKey($clientBrowser);                       // Räkna ut ett unikt värde för sessionen
                $this->setSessionUniqueKey($this->calculatedUniqueKey);                 // Tilldela variabeln det unika värdet
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
    public function setSaveLogin($bool) {
        $this->saveLogin = $bool;
        if($this->saveLogin === true) {
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
}