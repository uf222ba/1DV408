<?php
/**
 * Created by PhpStorm.
 * User: Ulrika Falk
 * Date: 2014-10-19
 * Time: 14:56
 */

namespace view;
/*
require_once 'vendors/Twig-1.16.2/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
*/
require_once("src/model/MainModel.php");

class MainView {

    private $mainModel;

    private $urlPageParam;
    private $urlActionParam;
    private $urlIdParam;

    private static $pageParam = "page";
    private static $actionParam = "action";
    private static $idParam = "passid";
    private static $updateData = "updateData";

    //--- session post
    private static $date = "date";
    private static $location = "location";
    private static $exercises = "exercises";

    private $postDate;
    private $postLocation;
    private $postExercises;
    private static $empty = "empty";
    private static $twigTemplatePath = "./src/view/templates";
    private static $twigFileExtension = ".twig";
    private static $twigCache = "cache";
    private static $twigCachePath = "./tmp/cache";


    public function __construct(\model\MainModel $model) {
        $this->mainModel = $model;
        $urlPageParam = NULL;
        $urlActionParam = NULL;
        $urlIdParam =  NULL;
    }

    public function render($pageToShow, $arrayWithTwigVariables) {
        $loader = new \Twig_Loader_Filesystem(self::$twigTemplatePath);
        $twig = new \Twig_Environment($loader, array(self::$twigCache => self::$twigCachePath,));

        echo $twig->render($pageToShow . self::$twigFileExtension, $arrayWithTwigVariables);
    }

    public function getPageParamFromURL() {
        $this->urlPageParam = $this->getGetVar(self::$pageParam);
        return $this->urlPageParam;
    }

    public function getActionParamFromURL() {
        $this->urlActionParam = $this->getGetVar(self::$actionParam);
        return $this->urlActionParam;
    }

    public function getIdParamFromURL() {
        $this->urlIdParam = $this->getGetVar(self::$idParam);
        return $this->urlIdParam;
    }

    public function setGetVar($key, $value) {
        $_GET[$key] = $value;
    }

    public function getGetVar($key) {
        if(isset($_GET[$key]))
            return $_GET[$key];
        return FALSE;
    }

    public function getPostLocationFromSession() {
        $this->postLocation = $this->getPostVar(self::$location);
        return $this->postLocation;
    }

    public function getPostDateFromSession() {
        $this->postDate = $this->getPostVar(self::$date);
        return $this->postLocation;
    }

    public function getPostExercisesFromSession() {
        $this->postExercises = $this->getPostVar(self::$exercises);
        return $this->postExercises;
    }

    public function getPostVar($key) {
        if(isset($_POST[$key]))
            return $_POST[$key];
        return FALSE;
    }

    public function getNewSession() {
        $values = array(self::$empty => self::$empty);
        return $values;
    }

    public function getNewSessionNotes() {
        $values = array(self::$date => $this->getPostVar(self::$date),
                        self::$location => $this->getPostVar(self::$location),
                        self::$exercises => $this->getPostVar(self::$exercises));
        return $values;
    }

    public function getUpdateSessionNotes() {
        $values = array(self::$idParam => $this->getIdParamFromURL());
        return $values;
    }

    public function getUpdateStart() {
        $postData = $_POST;
        $values = array(self::$idParam => $this->getIdParamFromURL(),
                        self::$updateData => $postData);
        return $values;
    }

    public function getDeleteStart() {
        $values = array(self::$idParam => $this->getIdParamFromURL());
        return $values;
    }
}