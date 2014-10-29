<?php
/*
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-09-16
 * Time: 15:24
 */

session_start();

require_once("HTMLView.php");
require_once("LoginController.php");

$viewLoginController = new LoginController();
$htmlBody = $viewLoginController->doLogin();

$view = new HTMLView();

$view->echoHTML($htmlBody);
