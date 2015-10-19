<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace config;

/**
 * Class LocalSettings
 * @package config
 */
class LocalSettings {

    /**
     * DATABASE CONFIGURATION
     */
    public static $DB_HOSTNAME = "127.0.0.1"; //"Obs använd 127.0.0.1, localhost triggar en bugg i PDO
    public static $DB_USERNAME = "xxx"; //an authorized user of the MySQL database
    public static $DB_PASSWORD = "xxx"; //my_username's password
    const DB_NAME = "xxx_db"; //the database we want to use.
    public static $DB_DSN = "mysql:host=127.0.0.1;dbname=xxx_db;charset=UTF8";

    /**
     * ERROR AND DEBUG CONFIGURATION
     */
    public static $DO_DEBUG = true;
    public static $ERROR_LOG = "error.log";

    public static $ROOT_PATH = 'Dogbook';
}

define('PATH_VIEWS', './templates');
define('PATH_VIEW_FILE_TYPE', '.twig');