<?php

namespace view;

require_once("src/model/LoginModel.php");

class CookieJar {

	private $model;
	private $cookieName = "LoginView::UserName";
	private $cookiePassword = "LoginView::Password";

	public function __construct(\model\ LoginModel $model) {
		$this->model = $model;
	}

	public function cookieNameForUserName() {
		return $this->cookieName;
	}

	public function cookieNameForPassword() {
		return $this->cookiePassword;
	}

	public function save($cookieName, $cookieValue) {
		setcookie($cookieName, $cookieValue, $this->cookieExpireTime());
	}

	public function remove($cookieName) {
		setcookie($cookieName, "" , time() -1);
	}

	public function cookieExpireTime() {
		return time() +30;
	}

	public function getClientIdentifer() {
		return $_SERVER["REMOTE_ADDR"];
	}	

	public function hasLoginCookies() {
		if(isset($_COOKIE[$this->cookieName]) && isset($_COOKIE[$this->cookiePassword]) == true) {
				return true;
			}
		
		return false;
	}

	public function getCookieUserName() {
		if($this->hasLoginCookies() == true) {
		return $_COOKIE[$this->cookieName];
	}
	return "";
	}

	public function getCookiePassword() {
		if($this->hasLoginCookies() == true) {
		return $_COOKIE[$this->cookiePassword];
		}
		return "";
	}
}