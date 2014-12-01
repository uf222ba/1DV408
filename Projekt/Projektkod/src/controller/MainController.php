<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Date:        2014-11-16
 * Laboration:  Projekt
 */

namespace controller;

require_once("src/view/MainView.php");
require_once("src/model/MainModel.php");

class MainController {

    private $url_page = null;
    private $url_action = null;
    private $url_param_1 = null;

    private $pageArray = array("start" => "Start",
                               "session" => "Session",
                               "sessionnotes" => "SessionNotes");

    private $mainView;
    private $mainModel;

    private static $get = "get";
    private static $start = "start";

    public function __construct() {
        $this->mainModel = new \model\MainModel();
        $this->mainView = new \view\MainView($this->mainModel);
        $this->setURLParametersFromView();
    }

    public function goToPage($userInformation) {

        $this->mainModel->setUserInfo($userInformation);

        if($this->mainView->getPageParamFromURL()) {
            // Anropa modellen att göra det den ska innan render-funktionen anropas
            // Modellfunktionen ska returnera en array med data som behövs för att rendera sidan

            $function = $this->url_action . $this->pageArray[$this->url_page];
            $parameters = self::$get . ucfirst($this->url_action) . $this->pageArray[$this->url_page];

            if($this->url_page && $this->url_action && $this->url_param_1) {
                $this->mainView->render($this->pageArray[$this->url_page], $this->mainModel->$function($this->mainView->$parameters()));
            } elseif($this->url_page && $this->url_action) {
                $this->mainView->render($this->pageArray[$this->url_page], $this->mainModel->$function($this->mainView->$parameters()));
            }  else {
                //Go to startpage
                $this->mainView->render(self::$start, $this->mainModel->start());
            }
        }
        else {
            //Go to startpage
            $this->mainView->render(self::$start, $this->mainModel->start());
        }
    }

    private function setURLParametersFromView() {
        $this->url_page = $this->mainView->getPageParamFromURL();
        $this->url_action = $this->mainView->getActionParamFromURL();
        $this->url_param_1 = $this->mainView->getIdParamFromURL();
    }
}