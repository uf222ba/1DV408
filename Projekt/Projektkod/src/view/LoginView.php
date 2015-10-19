<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace view;

/**
 * Class LoginView
 * Class for rendering the login view in the browser and to get and set information using input and output with the view
 * @package view
 */
class LoginView {
    private $loginModel;
    private $clientBrowser;
    private $ipAddress;

    /**
     * Constructor with dependency injection
     */
    public function __construct(\model\LoginModel $loginModel) {
        $this->loginModel = $loginModel;
    }

    /**
     * Function for rendering the login view
     */
    public function loginHTML() {
        $loader = new \Twig_Loader_Filesystem('./src/view/templates');
        $twig = new \Twig_Environment($loader, array('cache' => './tmp/cache',));
        $arr = array('message' => self::loginMessage($this->loginModel->getLoginDetails()->getLoginMessage()),
                     'username' => $this->loginModel->getUser()->getUsername());
        echo $twig->render('login.twig', $arr);
    }

    /**
     * Function for setting the user cookies in the browser
     */
    public function setCookiesInBrowser() {
        if(($this->loginModel->getLoginDetails()->getIsUserAuthenticated() == true) && ($this->loginModel->getLoginDetails()->getSaveLogin() == true));
            $this->setCookies();
    }

    /**
     * Function for rendering the logout view in the browser
     */
    public function logOutHTML(){
        if(($this->loginModel->getLoginDetails()->getIsUserAuthenticated() == true) && ($this->loginModel->getLoginDetails()->getSaveLogin() == true)) {  // Kolla så att användaren är autentiserad och om användaren ville spara användaruppgifterna
            $this->setCookies();                                                                                    // så skapas cookies
        }

        $loader = new \Twig_Loader_Filesystem('./src/view/templates');
        $twig = new \Twig_Environment($loader, array('cache' => './tmp/cache',));
        $arr = array();
        echo $twig->render('login.twig', $arr);
    }

    /**
     * Function for all the different types of login messages
     * @param $type integer value
     * @return string containing a message
     */
    private static function loginMessage($type) {
        $loginMsg = array();

        $loginMsg[0] = "Användarnamn saknas";
        $loginMsg[1] = "Lösenord saknas";
        $loginMsg[2] = "Felaktigt användarnamn och/eller lösenord";
        $loginMsg[3] = "Du har loggat ut";
        $loginMsg[4] = "Inloggning lyckades";
        $loginMsg[5] = "Inloggning lyckades och vi kommer ihåg dig nästa gång";
        $loginMsg[6] = "";
        $loginMsg[7] = "Inloggning lyckades via cookies";
        $loginMsg[8] = "Felaktig information i cookie";

        if (!isset($type))
            $type = 6;

        return $loginMsg[$type];
    }

    /**
     * Function that gets information from the querystring
     * @return string
     */
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

    /**
     * Function for getting the posted username in the login form
     * @return string username
     */
    public function getPostedUser() {
        return trim($_POST["username"]);
    }

    /**
     * Function for getting the posted password in the login form
     * @return string password
     */
    public function getPostedPassword() {
        return trim($_POST["password"]);
    }

    /**
     * Function for getting the checkbox value from the login form
     * @return bool true if the login should be saved
     */
    public function CheckboxSaveLogin() {
        if(isset($_POST["LoginView::Checked"])) {
            //echo "Ikryssad";
            return true;
        } else {
            //echo "EJ ikryssad";
            return false;
        }
    }

    /**
     * Function for generating a string contining time and date information
     * @return string
     */
    public function today() {
        setlocale(LC_ALL, "sv_SE", "sv_SE.utf-8", "sv", "swedish"); //http://www.webforum.nu/showthread.php?t=182908
        $todayString = ucwords(utf8_encode(strftime("%A"))) . " den " . date("j") . " ".  strftime("%B") . " &aring;r " . strftime("%Y") . ". Klockan &auml;r [" . date("H:i:s") . "].";
        return $todayString;
    }

    /**
     *  Function for getting values form the loginModel instance and setting the cookies in the browser
     */
    public function setCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUser()->getUsername(), time() + 120);
        setcookie("LoginView::Password",  $this->loginModel->getUser()->getPassword(), time() + 120);
    }

    /**
     * Function that checks if the cookies is set
     * @return bool true if cookies is set, otherwise false
     */
    public function getCookies() {
        if(isset($_COOKIE["LoginView::UserName"]) && isset($_COOKIE["LoginView::Password"])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function for making the cookies invalid
     */
    public function deleteCookies() {
        setcookie("LoginView::UserName", $this->loginModel->getUser()->getUsername(), time() - 3600);
        setcookie("LoginView::Password",  $this->loginModel->getUser()->getPassword(), time() - 3600);
    }
    // Hämta användarnamnet i kakan
    // Returnerar en sträng
    /**
     * @return mixed
     */
    public function getUserFromCookie(){
        if(isset($_COOKIE["LoginView::UserName"]))
            return $_COOKIE["LoginView::UserName"];
    }

    /**
     * Get the password from the cookie
     * @return string
     */
    public function getPasswordFromCookie(){
        if(isset($_COOKIE["LoginView::Password"]))
            return $_COOKIE["LoginView::Password"];
    }

    /**
     * Function for checking if the browser has a cookie with the sessionId
     * @return bool true if sessionId cookie exists, otherwise false
     */
    public function isSessionId() {
        if(isset($_COOKIE["PHPSESSID"])) {
            return true;
        }
        return false;
    }

    /**
     * Function for getting the http user agent
     * @return string with the name of the web browser
     */
    public function getClientBrowser() {
        $this->clientBrowser = $_SERVER["HTTP_USER_AGENT"];
        return $this->clientBrowser;
    }

    /**
     * Function for getting the IP address of the client
     * @return string containing the IP address
     */
    public function getClientIpAddress() {
        $this->ipAddress = $_SERVER["REMOTE_ADDR"];
        return $this->ipAddress;
    }
}