<?php

require_once("src/controller/LoginController.php");
require_once("../common/HTMLView.php");

$logincontroller = new \controller\LoginController();

$html = $logincontroller->doDisplay();

$view = new HTMLView();

$view->echoHTML($html);
