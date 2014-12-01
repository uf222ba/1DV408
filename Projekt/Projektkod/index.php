<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-10-22
 * Time: 05:10
 */

session_start();

require_once("vendors/Twig-1.16.2/lib/Twig/Autoloader.php");
Twig_Autoloader::register();

require_once("config/LocalSettings.php");

//require_once("HTMLView.php");
require_once("src/controller/LoginController.php");

$loginCtrl = new \controller\LoginController();
