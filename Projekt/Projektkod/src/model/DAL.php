<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;

require_once("./config/LocalSettings.php");

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
