<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace model;


class Session {

    static function setSessionVar($key, $value) {
        $_SESSION[$key] = $value;
    }

    static function getSessionVar($key) {
        if(isset($_SESSION[$key]))
            return $_SESSION[$key];
        return false;
    }
}
