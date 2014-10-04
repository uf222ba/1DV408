<?php

namespace controller;

require_once("src/view/LoginView.php");
require_once("src/model/LoginModel.php");

class LoginController {
	private $view;
	private $model;
	private $message = "";

	public function __construct() {
		$this->model = new \model\LoginModel();
		$this->view = new \view\LoginView($this->model);
	}

	public function doLogout() {
				$this->model->logOut();		
		}

	public function doLogoutWithCookies() {
		if ($this->view->hasLoginCookies() == true) {
			if ($this->view->hasLogOut() == true) {
				$this->model->logOut();
				$this->view->remove($this->view->cookieNameForUserName());
				$this->view->remove($this->view->cookieNameForPassword());
				return true;
			}
		}		
	}

	public function doLogin() {

			$inputUsername = $this->view->getUserName();
			$inputPassword = $this->view->getPassword();
			$ip = $this->view->getClientIdentifer();
			$userCookieName = $this->view->getCookieUserName();
			$userCookiePassword = $this->view->getCookiePassword();
			$currentTime = time();
			$browser = $this->view->getUserAgent();

			if ($this->model->logIn($inputUsername, $inputPassword, $userCookieName, $userCookiePassword, $ip, $currentTime, $browser) == true) {

				if($this->view->hasChecked() == true) {
					$cookiePassword = $this->model->createCookieInformation($browser, $currentTime);

					$this->view->save($this->view->cookieNameForUserName() , $inputUsername);
					$this->view->save($this->view->cookieNameForPassword() , $cookiePassword);
					$cryptPassword = $this->model->createCookieInformation($cookiePassword, $ip);
					$this->model->setCookieInformation($inputUsername, $cryptPassword, $this->view->cookieExpireTime());
				}
				return true;
			}
			return false;
	}

	public function doDisplay() {
		$didLogin = false;

		if($this->view->hasLogOut() == true) {
			$this->doLogOut();
		}

		if ($this->view->hasSubmit() == true || $this->view->hasLoginCookies() == true) {
			if ($this->model->isLoggedIn() == false) {
				$didLogin = $this->doLogin();
			}
        }

		if ($this->doLogoutWithCookies() == true) {
			return $this->view->showLoginForm($didLogin);
		}

		if ($this->model->isLoggedIn() == true && $this->model->isValidSession($this->view->getUserAgent()) == true) {
			return $this->view->showMemberSection($didLogin);
		}

        // Lägg till if-sats för att kolla om besökaren vill skapa en ny användare
        if ($this->view->hasChoosenRegisterUser() == true && !$this->view->hasNewUserCredentials() && !$this->view->hasLogOut()) {
//            echo "Registrera ny användare 1<br />";
            return $this->view->showRegisterNewUser();
        }

        if ($this->view->hasNewUserCredentials() == true) {     // Försöker en ny användare registrera sig?
//            echo "Registrera ny användare 2<br />";
            if($this->model->createNewUser($this->view->getUserName(), $this->view->getPassword(), $this->view->getPasswordRepeat()) == true){       // Kolla om de nya användaruppgifterna är ok
                return $this->view->showLoginForm($didLogin);
            }
            return $this->view->showRegisterNewUser();
        }

        return $this->view->showLoginForm($didLogin);
	}


}
