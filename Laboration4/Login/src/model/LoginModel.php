<?php

namespace model;

require_once("src/model/LoginDAL.php");

class LoginModel {

	private $loginDAL;
	private $cookieName;
	private $cookiePassword;
	private $cookieDate;
	private $fileName = "CookieInfo.txt";
	private $sessionName = "valid";
    private $validUserName;
    private $message = "";

	public function __construct() {
		session_start();

		$this->loginDAL = new \model\LoginDAL();
		$this->getCookieInformation();
        $this->validUserName = "";
	}

	public function isLoggedIn() {
		if (isset($_SESSION[$this->sessionName]) == true)
		 {
			return true;
		}	

		return false;
	}

	public function isValidSession($current) {
		if ($current == $_SESSION[$this->sessionName]) {
            return true;
		}
		return false;
	}

    public function logIn($username, $password, $userCookieUserName, $userCookiePassword, $clientIdentifer, $currentTime, $browser) {
        $newUserCookiePassword = $this->createCookieInformation($userCookiePassword, $clientIdentifer);

        if ($this->loginDAL->getUserCredentialsFromDB($username, $password) == true
            || $userCookieUserName == $this->cookieName && $newUserCookiePassword == $this->cookiePassword
            && $this->cookieDate > $currentTime == true) {
            $_SESSION[$this->sessionName] = $browser;
            $this->validUserName = $username;
            $_SESSION["user"] =  $this->validUserName;
            return true;
        }

        return false;
    }

    public function createNewUser($username, $password, $newPasswordRepeat) {

        // Om användarnamnet har otillåtna tecken
        if(strcmp(strip_tags($username), $username) != 0) {
            $username = strip_tags($username);
            $this->validUserName = $username;
            $this->message = "Användarnamnet innehåller ogiltiga tecken.";
            return false;
        }

        // Om användarnamnet är ok men lösenordet för kort
        if(strlen($username) >= 3 && strlen($password) < 6  ) {
            $this->message = "Lösenordet har för få tecken. Minst 6 tecken krävs.";
            return false;
        }
        // Om användarnamnet är för kort men lösenordet är ok
        if(strlen($username) < 3 && strlen($password) >= 6) {
            $this->message = "Användarnamnet har för få tecken. Minst 3 tecken krävs.";
            return false;
        }

        // Både användarnamn och lösenord är för korta

        if(strlen($username) < 3 && strlen($password) < 6) {
            $this->message = "Användarnamnet har för få tecken. Minst 3 tecken krävs.<br/>
                              Lösenordet har för få tecken. Minst 6 tecken krävs.";
            return false;
        }

        // Både användarnamn och lösenord är för tillräckligt långa, men lösenorden matchar inte vid jämförelse
        if(strlen($username) >= 3 && strlen($password) >= 6 && (strcmp($password, $newPasswordRepeat) != 0)) {
            $this->message = "Lösenorden matchar inte.";
            return false;
        }

        // Kolla om om användaren redan existerar.
        if($this->loginDAL->isUserInDB($username) == false) {
            if($this->loginDAL->createNewUser($username, $password) == true) {
                //Skicka tillbaka användaren till loginsidan med det nya användarnamnet ifyllt
                $this->validUserName = $username;
                return true;   // Om användaren inte finns i databasen, så skapas en användare
            }
        } else {
            $this->message = "Användarnamnet är redan upptaget";
        }
        return false;  // Användaren fanns redan i databasen, försök igen
    }

	public function logOut() {
			unset($_SESSION[$this->sessionName]);
            unset($_SESSION["user"]);
	}

    public function getValidUserName() {
        return $this->validUserName;
    }

	public function getNumLines() {
		$lines = @file($this->fileName);
		if ($lines === FALSE) {
			return 0;
		}
		return count($lines);
	}

	public function getCookieInformation() {
		$lines = $this->getNumLines();

		if ($lines == 0) {
			return;
		}

		else {
			$file = $this->fileName;
			$fileHandler = fopen($file, "r");
			$information = fread($fileHandler, filesize($file));
			fclose($fileHandler);
 
			$pieceofInformation = explode("," , $information);
			$this->cookieName = $pieceofInformation[0];
			$this->cookiePassword = $pieceofInformation[1];
			$this->cookieDate = $pieceofInformation[2];
		}
	}

	public function createCookieInformation($string, $salt) {
		return md5($string . $salt);
	}

    public function getMsg() {
        return $this->message;
    }

	public function setCookieInformation($username, $password, $currentTime) {
		$file = $this->fileName;
		$fileHandler = fopen($file, "w");
		fwrite($fileHandler, $username . "," . $password . "," . $currentTime);
		fclose($fileHandler);
	}
}