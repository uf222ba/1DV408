<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace controller;

require_once("src/view/LoginView.php");
require_once("src/model/LoginModel.php");
require_once("NavigationCtrl.php");

/**
 * Class LoginController
 * Class with functions for the login process
 * @package controller
 */

class LoginController {
    private $loginView;
    private $loginModel;
    private $navigationCtrl;

    const ACTIONLOGIN = "login";
    const ACTIONLOGOUT = "logout";

    public function __construct() {
        $this->loginModel = new \model\LoginModel();
        $this->loginView = new \view\LoginView($this->loginModel);
        $this->navigationCtrl = new \controller\NavigationCtrl();
        $this->doLogin();
    }

    /**
     * Function that decides which view would be rendered
     * @throws \Exception
     */
    public function doLogin() {
        $action = $this->loginView->getAction(); // Hämtar värdet från querystringen
        $this->loginModel->getLoginDetails()->setClientData($this->loginView->getClientBrowser(),
                                                            $this->loginView->getClientIpAddress());

        if($action == self::ACTIONLOGOUT) {
            self::logout();
        } elseif($this->loginModel->isSessionUniqueKeyValid()) {
            $this->navigationCtrl->goToPage($this->loginModel->getUser());
            $this->loginModel->getLoginDetails()->setLoginMessage(6);
        } elseif($this->loginView->getCookies() == true) {
            self::loginWithCookies();
        } elseif($action == self::ACTIONLOGIN) {
            self::login();
        } else {
            $this->loginView->loginHTML();
        }
    }

    /**
     * Function that will be called if the user tries to log in with cookies
     */
    private function loginWithCookies() {
        $this->loginModel->getLoginDetails()->setCookieLogin(true);
        $isUserValid = $this->loginModel->authenticateUser($this->loginView->getUserFromCookie(),   // Kollar om användaruppgifterna stämmer
                                                           $this->loginView->getPasswordFromCookie());
        if($isUserValid == true) {
            if($this->loginView->isSessionId() == true) {                       // Kolla om det finns någon sessionscookie, finns inte det, så ska inloggning via cookies skrivas ut
                $this->loginModel->getLoginDetails()->setLoginMessage(6);    // Sätter meddelandet till en tom sträng
            } else {
                $this->loginModel->getLoginDetails()->setLoginMessage(7);    // Ser till så att meddelandet "Inloggad via cookies" skrivs ut
            }
            $this->navigationCtrl->goToPage($this->loginModel->getUser());
        } else {
            $this->loginModel->getLoginDetails()->setLoginMessage(8);        // Användaren hade kakor med ogiltiga värden i. Meddelandet "Felaktig information i cookie" visas.
        }
    }

    /**
     * Function that will be called if the user tries to log in by posting username and password through the login form
     * @throws \Exception
     */
    private function login() {
        $this->loginModel->getLoginDetails()->setPasswordHash($this->loginView->getPostedPassword());    // Hasha det inmatade lösenordet
        $isUserValid = $this->loginModel->authenticateUser($this->loginView->getPostedUser(),           // Autentisera användaren
                                                           $this->loginModel->getLoginDetails()->getHashedPassword());

        if($isUserValid == true) { // Användaren är autentiserad
           if($this->loginView->CheckboxSaveLogin())
                $this->loginModel->saveCookieLogin();
                $this->loginView->setCookiesInBrowser();
                $this->navigationCtrl->goToPage($this->loginModel->getUser());
        }
        else
           $this->loginView->loginHTML();
    }

    /**
     * Function for logging out the user
     */
    private function logout() {
        $this->loginModel->logOutUser();                                        // Ta bort kakor och värden från sessionen
        $this->loginView->deleteCookies();

        if(strlen($this->loginModel->getLoginDetails()->getSessionUniqueKey()) < 1)     // Kolla om den unika nyckeln är borttagen i sessionsvariabeln
            $this->loginModel->getLoginDetails()->setLoginMessage(6);                                       // Då har omladdning av sidan skett och då ska inget meddelande visas

        $this->loginView->loginHTML();
    }
}
