<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace controller;

require_once("src/view/LoginView.php");
require_once("src/model/LoginModel.php");
require_once("MainController.php");

class LoginController {
    private $loginView;
    private $loginModel;

    public function __construct() {
        $this->loginModel = new \model\LoginModel();
        $this->loginView = new \view\LoginView($this->loginModel);
        $this->mainCtrl = new \controller\MainController();
        $this->doLogin();
    }

    // Funktion för att avgöra vilken vy som ska visas för användaren
    public function doLogin() {
        $actionLogin = "login";
        $actionLogOut = "logout";

        $action = $this->loginView->getAction(); // Hämtar värdet från querystringen
        $this->loginModel->calculateSessionUniqueKey($this->loginView->getClientBrowser()); // Beräkna ett unik nyckel för den aktuella sessionen, värdet sätts i loginModel-objektet

        if(strlen($this->loginModel->getSessionUniqueKey()) > 0 && ($action !== $actionLogOut)) {    // Om den unika nyckeln i sessionen och URL:ens querystring inte innehåller "logout"
            if($this->loginModel->isSessionUniqueKeyValid() == true) {                          // Kollar om det kalkylerade värdet för den unika nyckeln och nyckeln i den faktiska sessionen är likadana
                $this->loginModel->setLoginMessage(6);                                          // Sätter vilket meddelande som ska visas för användaren
                return $this->mainCtrl->goToPage($this->loginModel->getUserInformation());
                //return $this->loginView->logOutHTML();                                        // Användaren har lyckats logga in och nu visas vyn för att kunna logga ut
            } else {
            return $this->loginView->loginHTML();                                               // Nyckeln i sessionen och det beräknade värdet stämmer inte, så användaren får försöka igen
            }
        }

        if(($this->loginView->getCookies() == true) && ($action !== $actionLogOut)) {                // Om det finns kakor och det inte står "logout" i querystringen
            $this->loginModel->cookieLogin();
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getUserFromCookie(),   // Kollar om användaruppgifterna stämmer
                                                               $this->loginView->getPasswordFromCookie(),
                                                               $this->loginView->getClientBrowser());
            if($isUserValid == true) {
                if($this->loginView->isSessionId() == true) { // Kolla om det finns någon sessionscookie, finns inte det, så ska inloggning via cookies skrivas ut
                    $this->loginModel->setLoginMessage(6);    // Sätter meddelandet till en tom sträng
                } else {
                    $this->loginModel->setLoginMessage(7);    // Ser till så att meddelandet "Inloggad via cookies" skrivs ut
                }
                return $this->mainCtrl->goToPage($this->loginModel->getUserInformation());
                //return $this->loginView->logOutHTML();        // Användaren skickas till vyn för de inloggade, dvs det är möjligt att logga ut
            } else {
                $this->loginModel->setLoginMessage(8);        // Användaren hade kakor med ogiltiga värden i. Meddelandet "Felaktig information i cookie" visas.
            return $this->loginView->loginHTML();             // Användaren skickas till login-vyn
            }
        }

        if(empty($action)) {                                  // Om querystringen är tom...
            return $this->loginView->loginHTML();             // Skicka användaren till login-vyn
        } elseif($action === $actionLogin) {                       // Om querystringen innehåller strängen "login"
            $hashedPassword = $this->loginModel->setPasswordHash($this->loginView->getPostedPassword());    // Hasha det inmatade lösenordet
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getPostedUser(),           // Autentisera användaren
                                                               $hashedPassword,
                                                               $this->loginView->getClientBrowser());
            if($isUserValid == true) {                                                                      // Användaren är autentiserad
                $this->loginModel->setSaveLogin($this->loginView->CheckboxSaveLogin(), $this->loginView->getClientBrowser());  // Ska cookies för användaren sättas?
                if($this->loginView->CheckboxSaveLogin())
                    $this->loginView->setCookiesInBrowser();
                $this->mainCtrl->goToPage($this->loginModel->getUserInformation());
            } else {
                return $this->loginView->loginHTML();                                                       // Användaren försökte logga in med felaktiga uppgifter och skickas till login-vyn
            }

        } elseif($action === $actionLogOut) {                 // Om strängen "logout" finns i querystringen...
            $this->loginModel->logOutUser();                  // Ta bort kakor och värden från sessionen
            $this->loginView->deleteCookies();

            if($action === $actionLogOut && strlen($this->loginModel->getSessionUniqueKey()) < 1) // Kolla om den unika nyckeln är borttagen i sessionsvariabeln
                $this->loginModel->setLoginMessage(6);                                       // Då har omladdning av sidan skett och då ska inget meddelande visas

            $this->loginModel->deleteSessionVariables();
            return $this->loginView->loginHTML();             // Skicka användaren till login-vyn
        } else {
            return $this->loginView->loginHTML();             // I alla andra fall, så skickas användaren till login-vyn
        }
    }
}
