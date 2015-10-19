<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

/**
 *  OBS Denna kod är byggd utifrån Daniels exempelprojekt Portfolio!!!
 *  https://github.com/dntoll/1dv408-HT14/tree/master/Portfolio
 */

namespace controller;
require_once('src/view/NavigationView.php');
require_once('AppController.php');

/**
 * Class NavigationCtrl
 * Class for determine what actions should be taken
 * @package controller
 */

class NavigationCtrl
{
    /**
     * Function for deciding which view should be rendered in the browser depending on what the user has chose.
     * @param \model\User $userObject
     * @throws \Exception
     */
    public function goToPage(\model\User $userObject) {
        $controller;

        try {
            switch (\view\NavigationView::getAction()) {
                case \view\NavigationView::$actionAddSession:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("session", $controller->addSession());
                    break;
                case \view\NavigationView::$actionAddSessionNotes:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("sessionnotes", $controller->addSessionNotes(\view\NavigationView::getDate(),
                                                                                                     \view\NavigationView::getLocation(),
                                                                                                     \view\NavigationView::getExercises()));
                    break;
                case \view\NavigationView::$actionUpdateSessionNotes:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("sessionnotes", $controller->updateSessionNotes(\view\NavigationView::getId()));
                    break;
                case \view\NavigationView::$actionSaveSessionNotes:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("start", $controller->saveSessionNotes(\view\NavigationView::getId(),
                                                                                               \view\NavigationView::getSessionNotes(),
                                                                                               \view\NavigationView::getExercise()));
                    break;
                case \view\NavigationView::$actionDeleteSession:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("start", $controller->deleteSession(\view\NavigationView::getId()));
                    break;
                case \view\NavigationView::$actionChangeDog:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("start", $controller->changeDog(\view\NavigationView::getId()));
                    break;
                default:
                    $controller = new AppController($userObject);
                    return \view\NavigationView::render("start", $controller->showAllSessions());
                    break;
            }
        } catch (\Exception $e) {

            error_log(date("Y-m-d H:s"). " " . $e->getMessage() . "\n", 3, \config\LocalSettings::$ERROR_LOG);
            if (\config\LocalSettings::$DO_DEBUG) {
                throw $e;
            } else {
                \view\NavigationView::RedirectToErrorPage();
                die();
            }
        }
    }
}