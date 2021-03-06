<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("./config/LocalSettings.php");

/**
 * Abstract Class DAL
 * Abstract base class responsible for the database connection and the database handle
 * @package model
 */
abstract class DAL
{
    protected $databaseHandle;

    protected function connection() {
        if($this->databaseHandle == NULL)
            $this->databaseHandle = new \PDO(\config\LocalSettings::$DB_DSN, \config\LocalSettings::$DB_USERNAME, \config\LocalSettings::$DB_PASSWORD);

        $this->databaseHandle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->databaseHandle;
    }
}
