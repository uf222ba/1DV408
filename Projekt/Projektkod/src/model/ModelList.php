<?php
/**
 * Student:     uf222ba
 * Name:        Ulrika Falk
 * Laboration:  Dogbook
 * Date:        2015-10-10
 */

namespace model;

/**
 * Class ModelList is a base class
 * It has de basic functionality
 * An instance variable of the array type
 * An add function for adding new items
 * A count function for retrieving the number of elements in the list
 * A function for getting the instance array
 * @package model
 */
class ModelList
{
    protected $theList = array();

    public function  add($item) {
        $this->theList[] = $item;
    }

    public function count() {
        return count($this->theList);
    }

    public function getList() {
        return $this->theList;
    }
}