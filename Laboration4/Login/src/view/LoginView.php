<?php

namespace view;

require_once("src/model/LoginModel.php");
require_once("src/view/CookieJar.php");

class LoginView {
	private $model;
	private $cookieJar;
	private $userNameLocation = "username";
	private $passwordLocation = "password";
    private $passwordLocationRepeat = "passwordRepeat";  //Tillagd för att repetera lösenordet
    private $logoutLocation = "logout";
	private $message = "";

	public function __construct(\model\ LoginModel $model) {
		$this->model = $model;
		$this->cookieJar = new \view\CookieJar($this->model);
	}

	public function cookieNameForUserName() {
		return $this->cookieJar->cookieNameForUserName();
	}

	public function cookieNameForPassword() {
		return $this->cookieJar->cookieNameForPassword();
	}	

	public function save($cookieName, $cookieValue) {
		$this->cookieJar->save($cookieName, $cookieValue);
	}

	public function remove($cookieName) {
		$this->cookieJar->remove($cookieName);
	}

	public function hasLoginCookies() {
		return $this->cookieJar->hasLoginCookies();
	}

	public function cookieExpireTime() {
		return $this->cookieJar->cookieExpireTime();
	}

	public function getCookieUserName() {
		return $this->cookieJar->getCookieUserName();
	}

	public function getCookiePassword() {
		return $this->cookieJar->getCookiePassword();
	}	

	public function getUserAgent() {
		return $_SERVER["HTTP_USER_AGENT"];
	}

	public function getClientIdentifer() {
		return $_SERVER["REMOTE_ADDR"];
	}

	public function hasChecked() {
		if (isset($_POST["checkbox"]) == true) {
			return true;
		}
		return false;
	}

	public function hasUserName() {
		if(!empty($_POST[$this->userNameLocation]) == true) {
			return true;
		}
		return false;
	}

	public function hasPassword() {
		if(!empty($_POST[$this->passwordLocation]) == true) {
			return true;
		}
		return false;
	}

    public function hasNewRegisteredUserName() { // Har precis registrerat ett användarnamn
        if(strlen($this->model->getValidUserName()) > 2) {
            return true;
        }
        return false;
    }

    public function getUserName() {
        if($this->hasNewRegisteredUserName() == true) {  // Eller om användarnamn längre än två tecken är satt i modellen.
            return $this->model->getValidUserName();
        } elseif($this->hasUserName() == true) {
            return $_POST[$this->userNameLocation];
        } elseif($this->hasNewRegisteredUserName() == true) {  // Eller om användarnamn längre än två tecken är satt i modellen.
            return $this->model->getValidUserName();
        } elseif (isset($_SESSION["user"]) == true) {
            return $_SESSION["user"];
        }
    }

	public function getPassword() {
		if($this->hasPassword() == true) {
		return htmlentities($_POST[$this->passwordLocation]);
		}
	}

    public function getPasswordRepeat() {
        if($this->hasPassword() == true) {
            return htmlentities($_POST[$this->passwordLocationRepeat]);
        }
    }

	public function hasSubmit() {
		if(isset($_POST["submit"]) == true) {
			return true;
		}
		return false;
	}

	public function hasLogOut () {
		if(isset($_POST[$this->logoutLocation]) == true) {
			return true;
		}
		return false;
	}

    public function hasNewUserCredentials() { //Om användaren postat formuläret
        if(isset($_POST["registerNewUser"]) == true)
        {
            return true;
        }
        return false;
    }

    // Vill besökaren skapa en ny användare?
    public function hasChoosenRegisterUser() {
        if(key($_GET) == "register") { //kolla om ordet register finns i querystringen
            return true;
        }
        return false;
    }

	public function getDate() {
		setlocale(LC_ALL, "swedish");

		$date = "";

		$dayofWeek = utf8_encode(ucfirst(strftime("%A")));
		$day = strftime("%d");
		$month = ucfirst(strftime("%B"));
		$year = strftime("%Y");
		$time = strftime("%X");

		$date .=   $dayofWeek . ",  den " . $day . " " . $month . " år " . $year . ". Klockan är [" . $time . "].";

		return $date;
	}

	public function showLoginForm($didLogin) {
		$date = $this->getDate();
		$username = "";
		$checked = "";

		if ($this->hasLogOut() == true) {
			$this->message .= "</br> Utloggning lyckades </br> </br>";
		}
		else {
				if ($this->cookieJar->hasLoginCookies() == true && $didLogin == false) {
					$this->message .= "</br>Felaktig information i kakan </br> </br>";
					$this->remove($this->cookieNameForUserName());
					$this->remove($this->cookieNameForPassword());
				}				
		}

		if ($this->hasSubmit() == true) {
			if ($this->hasUserName() == false) {
				$this->message .= "</br> Användarnamnet saknas </br> </br>";
			}
			else {
				if ($this->hasPassword() == false) {
					$this->message .= "</br> Lösenord saknas </br> </br>";

					if ($this->hasUserName() == true) {
						$username = $this->getUserName();
					}

					if($this->hasChecked() == true) {
						$checked .= "checked";
					}					
				}
			}

			if ($this->hasUserName() && $this->hasPassword() == true) {
				if ($this->model->isLoggedIn() == false) {
					$this->message .= "</br> Felaktigt användarnamn och/eller lösenord </br> </br>";
					$username .= $this->getUserName();

					if($this->hasChecked() == true) {
						$checked .= "checked";
					}
				}
			}
		}

        if ($this->hasNewRegisteredUserName() == true && $this->hasNewUserCredentials() == true) {  // Om registreringen av ny användare lyckades
            if ($this->model->isLoggedIn() == false) {
                $this->message .= "</br> Registrering av ny användare lyckades</br> </br>";
                $username .= $this->getUserName();
            }
        }

        if (strlen($this->message) == 0) {
            $this->message = $this->model->getMsg();
        }

		$htmlbody =
		"<form method='post'>
        <a href='?register' >Registrera ny användare</a>
		<h2>Ej inloggad</h2>
		<fieldset>
		<legend>Logga in - Skriv in användarnamn och lösenord</legend>

		$this->message

		Användarnamn:
		<input type='text' name='$this->userNameLocation' value='$username' maxlength='35'>

		Lösenord:
		<input type='password' name='$this->passwordLocation' maxlength='35'>

		Håll mig inloggad:
		<input type='checkbox' name='checkbox' $checked>

		<input type='submit' name='submit' value='Logga in'>

		</fieldset>

		</br>

		$date

		</form>";

		return $htmlbody;
	}

	public function showMemberSection($didLogin) {
		$date = $this->getDate();
        $username = $this->getUserName();

		if($this->hasSubmit() == true) {

			if ($this->hasChecked() == true) {
				$this->message .= "</br> Inloggning lyckades och vi kommer att komma ihåg dig nästa gång </br> </br>";
			}
			else {
				$this->message .= "</br> Inloggning lyckades </br> </br>";
			}
		}
		else {
			if($this->cookieJar->hasLoginCookies() == true && $didLogin == true) {
				$this->message .= "</br> Inloggning lyckades via cookies </br> </br>";
			}
		}

		$htmlbody = 
		"<form method='post'>
		<h2>$username är inloggad</h2>

		$this->message

		<input type='submit' value='Logga ut' name='$this->logoutLocation'>

		</br>
		</br>

		$date

		</form>

		";

		return $htmlbody;
	}

    public function showRegisterNewUser() {
        $date = $this->getDate();
        $username = $this->getUserName();

        if (strlen($this->message) == 0) {
            $this->message = $this->model->getMsg();
        }

        $htmlbody =
        "<form method='post'>
        <a href='?' >Tillbaka</a>
		<h2>Ej inloggad. Registrera användare.</h2>
		<fieldset>
		<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>

		$this->message

		<p>
		Användarnamn:
		<input type='text' name='$this->userNameLocation' value='$username' maxlength='35'>
        </p>
        <p>
		Lösenord:
		<input type='password' name='$this->passwordLocation' maxlength='35'>
        </p>
        <p>
		Repetera lösenord:
		<input type='password' name='$this->passwordLocationRepeat' maxlength='35'>
        </p>
        <p>
		Skicka:
		<input type='submit' name='registerNewUser' value='Registrera'>
        </p>
		</fieldset>

		</ br>

		$date

		</form>";

        return $htmlbody;
    }
}