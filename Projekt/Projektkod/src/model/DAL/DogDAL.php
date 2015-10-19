<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

require_once("DAL.php");
require_once("src/model/Dog.php");
require_once("src/model/ModelList.php");

/**
 * Class DogDAL inherits the DAL class
 * Class with functions for handling dog data
 * @package model
 */
class DogDAL extends DAL
{
    private $dbConnection;
    private $result;

    public function __construct() {
        $this->dbConnection = $this->connection();
        $this->result = null;
    }

    /**
     * Method for getting dogs from the database
     * and add them to ModelList object
     * @param $userId
     * @return null|ModelList
     */
    public function getDogs($userId) {
        $dogs = new ModelList();

        $sql = "SELECT hundar.hundid
	                  , hundar.hundnamn
	                  , hundar.profilbild
	                  , hundar.vald
                FROM hundar
                WHERE hundar.userid_fk = :userid
                ORDER BY hundar.vald DESC";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":userid" => $userId));
        $pdoStmt->setFetchMode(\PDO::FETCH_ASSOC);
        $this->result = $pdoStmt->fetchAll();

        if(count($this->result) > 0){
            foreach($this->result as $dog) {
                $id = $dog['hundid'];
                $name = $dog['hundnamn'];
                $img = $dog['profilbild'];
                $chose = $dog['vald'];
                $d = new Dog($id, $name, $img, $chose);

                $dogs->add($d);
            }
            return $dogs;
        }
        return null;
    }

    /**
     * Function for changing the dog of choice
     * @param $userId
     * @param $dogId
     */
    public function updateSelectedDog($userId, $dogId) {

        $sql = "UPDATE hundar
	            SET hundar.vald = 0
                WHERE hundar.userid_fk = :userid";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":userid" => $userId));

        $sql = "UPDATE hundar
	            SET hundar.vald = 1
                WHERE hundar.userid_fk = :userid
                AND hundar.hundid = :dogid";

        $pdoStmt = $this->dbConnection->prepare($sql);
        $pdoStmt->execute(array(":userid" => $userId,
                                 ":dogid" => $dogId));
    }
}