<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace view;

/**
 * Class NavigationView
 * Class for rendering the user view in the browser
 * and getting the user input
 * @package view
 *
 * !!! - This class has it origin in the Portfolio project: https://github.com/dntoll/1dv408-HT14/tree/master/Portfolio
 * !!! - I've made som changes.
 */
class NavigationView
{
    private static $action = 'action';
    private static $id = 'id';

    public static $actionStart = 'start';
    public static $actionAddSession = 'addSession';
    public static $actionAddSessionNotes = 'addSessionNotes';
    public static $actionUpdateSessionNotes = 'updateSessionNotes';
    public static $actionSaveSessionNotes = 'saveSessionNotes';
    public static $actionDeleteSession = 'deleteSession';
    public static $actionChangeDog = 'changeDog';

    // session post
    private static $date = "date";
    private static $location = "location";
    private static $exercises = "exercises";
    private static $sessionNotes = "sessionnotes";
    private static $exercise = "exercise";

    private static $twigTemplatePath = "./src/view/templates";
    private static $twigFileExtension = ".twig";
    private static $twigCache = "cache";
    private static $twigCachePath = "./tmp/cache";

    /**
     * Function for rendering the twig template with the twigArray containing the necessary data
     * @param $pageToShow the name of the twig template file without the file extension
     * @param $arrayWithTwigVariables the associative array containing the data that is used for
     * showing the accurate information at the web page
     */
    public static function render($pageToShow, $arrayWithTwigVariables) {
        $loader = new \Twig_Loader_Filesystem(self::$twigTemplatePath);
        $twig = new \Twig_Environment($loader, array('cache'=> false, 'debug' => true,));
        // ta bort kommentarerna för att slå på cachning  $twig = new \Twig_Environment($loader, array(self::$twigCache => self::$twigCachePath,));
        //$twig = new Twig_Environment($loader, array('debug' => true));

        echo $twig->render($pageToShow . self::$twigFileExtension, $arrayWithTwigVariables);
    }

    /**
     * Function for getting the action from the querystring
     * @return string
     */
    public static function getAction() {
        if (isset($_GET[self::$action]))
            return $_GET[self::$action];
        return self::$actionStart;
    }

    /**
     * Function for getting the id from the querystring
     * @return null
     */
    public static function getId() {
        if (isset($_GET[self::$id]))
            return $_GET[self::$id];
        return NULL;
    }

    /**
     * Function for getting sessionnotes from the posted form
     * @return string with sessionnotes for saving to database
     */
    public static function getSessionNotes() {
        return self::getPostVar(self::$sessionNotes);
    }

    /**
     * Function for getting 'exercise' it should have been exercises
     * for saving in the database
     * @return array with exercises
     */
    public static function getExercise() {
        return self::getPostVar(self::$exercise);
    }

    /**
     * Function for getting the location posted in the form
     * @return string with location information
     */
    public static function getLocation() {
        return self::getPostVar(self::$location);
    }

    /**
     * Function for getting the date from the posted form
     * @return string containing the date
     */
    public static function getDate() {
        return self::getPostVar(self::$date);
    }

    /**
     * Function for getting the exercises from the posted form
     * @return array with the exercises
     */
    public static function getExercises() {
        return self::getPostVar(self::$exercises);
    }

    /**
     * Wrapper function for getting data from the $_POST superglobal
     * @param $key
     * @return string or bool if no key is found false
     */
    public static function getPostVar($key) {
        if(isset($_POST[$key]))
            return $_POST[$key];
        return FALSE;
    }

    /**
     * Function for redirecting to the error page
     *
     * !!! - This function is from the Portfolio project: https://github.com/dntoll/1dv408-HT14/tree/master/Portfolio
     */
    public static function RedirectToErrorPage() {
        header('Location: /' . \config\LocalSettings::$ROOT_PATH. '/error.html');
    }
}