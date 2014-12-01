<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-10-19
 * Time: 13:02
 */

namespace config;

class LocalSettings {

    //***** DATABASE CONFIGURATION ******//
    public static $DB_HOSTNAME = "127.0.0.1"; //"Obs använd 127.0.0.1, localhost triggar en bugg i PDO
    public static $DB_USERNAME = "xxx"; //an authorized user of the MySQL database
    public static $DB_PASSWORD = "xxx"; //my_username's password
    public static $DB_NAME = "php_db"; //the database we want to use.
    public static $DB_DSN = "mysql:host=127.0.0.1;dbname=php_db;charset=UTF8";
}

define('PATH_VIEWS', './templates');
define('PATH_VIEW_FILE_TYPE', '.twig');