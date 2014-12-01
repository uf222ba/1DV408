<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace view;

//require_once("../model/LoginModel.php");

class LoginView {
    private $loginModel;
    private $clientBrowser;

    public function __construct(\model\LoginModel $loginModel) {
        $this->loginModel = $loginModel;
    }
    // Kod för login-vyn
    public function loginHTML() {
        $loader = new \Twig_Loader_Filesystem('./src/view/templates');
        $twig = new \Twig_Environment($loader, array('cache' => './tmp/cache',));
        $arr = array();
        echo $twig->render('login.twig', $arr);
    }

    public function setCookiesInBrowser() {
        if(($this->loginModel->getIsUserAuthenticated() == true) && ($this->loginModel->getSaveLogin() == true));
            $this->setCookies();
    }

    // Kod för den vy som visas när man loggat in
    public function logOutHTML(){
        if(($this->loginModel->getIsUserAuthenticated() == true) && ($this->loginModel->getSaveLogin() == true)) {  // Kolla så att användaren är autentiserad och om användaren ville spara användaruppgifterna
            $this->setCookies();                                                                                    // så skapas cookies
        }

        $loader = new \Twig_Loader_Filesystem('./src/view/templates');
        $twig = new \Twig_Environment($loader, array('cache' => './tmp/cache',));
        $arr = array();
        echo $twig->render('login.twig', $arr);
    }

    // Funktion för alla olika meddelanden
    // Parameter är ett heltal
    // Returnerar en sträng beroende på vilken parameter som givits vid anrop av funktionen
    public function loginMessage($type) {
        $loginMsg = array();

        $loginMsg[0] = "<p>Användarnamn saknas</p>";
        $loginMsg[1] = "<p>Lösenord saknas</p>";
        $loginMsg[2] = "<p>Felaktigt användarnamn och/eller lösenord</p>";
        $loginMsg[3] = "<p>Du har loggat ut</p>";
        $loginMsg[4] = "<p>Inloggning lyckades</p>";
        $loginMsg[5] = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
        $loginMsg[6] = "";
        $loginMsg[7] = "<p>Inloggning lyckades via cookies</p>";
        $loginMsg[8] = "<p>Felaktig information i cookie</p>";

        return $loginMsg[$type];
    }

    // Funktion som kollar om querystringen innehåller login, logout eller saknar värde
    // Returnerar en sträng
    public function getAction() {
        if(key($_GET) == "login")
            $action = "login";
        elseif(key($_GET) == "logout") {
            $action = "logout";
        } else {
            $action = "";
        }
        return $action;
    }
    // Funktion för att hämta det postade värdet för användarnamnet
    // Returnerar användarnamnet
    public function getPostedUser() {
        return $_POST["username"];
    }
    // Funktion för att hämta det postade värdet för lösenordet
    // Returnerar användarnamnet
    public function getPostedPassword() {
        return $_POST["password"];
    }
    // Funktion för att kolla om användaren klickat i att inloggningen ska sparas i cookies
    // Returnerar true eller false
    public function CheckboxSaveLogin() {
        if(isset($_POST["LoginView::Checked"])) {
            //echo "Ikryssad";
            return true;
        } else {
            //echo "EJ ikryssad";
            return false;
        }
    }
    // Funktion för att generera en sträng som innehåller tid- och datuminformation
    // Returnerar en sträng
    public function today() {
        setlocale(LC_ALL, "sv_SE", "sv_SE.utf-8", "sv", "swedish"); //http://www.webforum.nu/showthread.php?t=182908
        $todayString = ucwords(utf8_encode(strftime("%A"))) . " den " . date("j") . " ".  strftime("%B") . " &aring;r " . strftime("%Y") . ". Klockan &auml;r [" . date("H:i:s") . "].";
        return $todayString;
    }
    // Funktion som hämtar värdena från modellen och sätter cookies i webbläsaren
    public function setCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUserName(), time() + 120);
        setcookie("LoginView::Password",  $this->loginModel->getPassword(), time() + 120);
    }
    // Funktion som kollar om cookies är satta
    // Returnerar true om cookies finns, annars falskt
    public function getCookies() {
        if(isset($_COOKIE["LoginView::UserName"]) && isset($_COOKIE["LoginView::Password"])) {
            return true;
        } else {
            return false;
        }
    }
    // Funktion för att göra kakorna utgångna, dvs göra dem ogiltiga
    public function deleteCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUserName(), time() - 3600);
        setcookie("LoginView::Password",  $this->loginModel->getPassword(), time() - 3600);
    }
    // Hämta användarnamnet i kakan
    // Returnerar en sträng
    public function getUserFromCookie(){
        if(isset($_COOKIE["LoginView::UserName"]))
            return $_COOKIE["LoginView::UserName"];
    }
    // Hämta lösenordet i kakan
    // Returnerar en sträng
    public function getPasswordFromCookie(){
        if(isset($_COOKIE["LoginView::Password"]))
            return $_COOKIE["LoginView::Password"];
    }
    // Funktion för att kolla om webbläsaren fått en kaka med sessionsid:t
    // Returnerer sant om den har fått det, falskt i annat fall
    public function isSessionId() {
        if(isset($_COOKIE["PHPSESSID"])) {
            return true;
        }
        return false;
    }
    // Funktion för att hämta vilken webbläsarklient som användaren har
    // Returnerar en sträng med aktuellt webbläsarnamn
    public function getClientBrowser() {
        $this->clientBrowser = $_SERVER["HTTP_USER_AGENT"];
        return $this->clientBrowser;
    }
}