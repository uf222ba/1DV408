<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:22
 */

require_once("LoginView.php");
require_once("LoginModel.php");

class LoginController {
    private $loginView;
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
        $this->loginView = new LoginView($this->loginModel);
    }

    // Funktion för att avgöra vilken vy som ska visas för användaren
    public function doLogin() {
        var_dump($_COOKIE);
        $action = $this->loginView->getAction(); // Hämtar värdet från querystringen
        $this->loginModel->calculateSessionUniqueKey($this->loginView->getClientBrowser()); // Beräkna ett unik nyckel för den aktuella sessionen, värdet sätts i loginModel-objektet

        if(strlen($this->loginModel->getSessionUniqueKey()) > 0 && ($action !== "logout")) {    // Om den unika nyckeln i sessionen och URL:ens querystring inte innehåller "logout"
            if($this->loginModel->isSessionUniqueKeyValid() == true) {                          // Kollar om det kalkylerade värdet för den unika nyckeln och nyckeln i den faktiska sessionen är likadana
                $this->loginModel->setLoginMessage(6);                                          // Sätter vilket meddelande som ska visas för användaren
                return $this->loginView->logOutHTML();                                          // Användaren har lyckats logga in och nu visas vyn för att kunna logga ut
            } else {
            return $this->loginView->loginHTML();                                               // Nyckeln i sessionen och det beräknade värdet stämmer inte, så användaren får försöka igen
            }
        }

        if(($this->loginView->getCookies() == true) && ($action !== "logout")) {                // Om det finns kakor och det inte står "logout" i querystringen
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getUserFromCookie(),   // Kollar om användaruppgifterna stämmer
                                                               $this->loginView->getPasswordFromCookie(),
                                                               $this->loginView->getClientBrowser());
            if($isUserValid == true) {
                if($this->loginView->isSessionId() == true) { // Kolla om det finns någon sessionscookie, finns inte det, så ska inloggning via cookies skrivas ut
                    $this->loginModel->setLoginMessage(6);    // Sätter meddelandet till en tom sträng
                } else {
                    $this->loginModel->setLoginMessage(7);    // Ser till så att meddelandet "Inloggad via cookies" skrivs ut
                }
                return $this->loginView->logOutHTML();        // Användaren skickas till vyn för de inloggade, dvs det är möjligt att logga ut
            } else {
                $this->loginModel->setLoginMessage(8);        // Användaren hade kakor med ogiltiga värden i. Meddelandet "Felaktig information i cookie" visas.
            return $this->loginView->loginHTML();             // Användaren skickas till login-vyn
            }
        }

        if(empty($action)) {                                  // Om querystringen är tom...
            return $this->loginView->loginHTML();             // Skicka användaren till login-vyn
        } elseif($action === "login") {                       // Om querystringen innehåller strängen "login"
            $hashedPassword = $this->loginModel->setPasswordHash($this->loginView->getPostedPassword());    // Hasha det inmatade lösenordet
            $isUserValid = $this->loginModel->authenticateUser($this->loginView->getPostedUser(),           // Autentisera användaren
                                                               $hashedPassword,
                                                               $this->loginView->getClientBrowser());
            if($isUserValid == true) {                                                                      // Användaren är autentiserad
                $this->loginModel->setSaveLogin($this->loginView->CheckboxSaveLogin());                     // Ska cookies för användaren sättas?
                return $this->loginView->logOutHTML();                                                      // Användaren skickas till vyn för inloggade, dvs det är möjligt att logga ut
            } else {
                return $this->loginView->loginHTML();                                                       // Användaren försökte logga in med felaktiga uppgifter och skickas till login-vyn
            }

        } elseif($action === "logout") {                      // Om strängen "logout" finns i querystringen...
            $this->loginModel->logOutUser();                  // Ta bort kakor och värden från sessionen
            $this->loginView->deleteCookies();

            if($action === "logout" && strlen($this->loginModel->getSessionUniqueKey()) < 1) // Kolla om den unika nyckeln är borttagen i sessionsvariabeln
                $this->loginModel->setLoginMessage(6);                                       // Då har omladdning av sidan skett och då ska inget meddelande visas

            $this->loginModel->deleteSessionVariables();
            return $this->loginView->loginHTML();             // Skicka användaren till login-vyn
        } else {
            return $this->loginView->loginHTML();             // I alla andra fall, så skickas användaren till login-vyn
        }
    }
}
