<?php

//* Class for storing unavilable staff */

class Unavailability{

    //private fields for storing unavailable staff data
    private $id, $username, $date_start, $date_end;

    //constructor initialising fields with data from DB
    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->username = $dbRow['username'];
        $this->date_start = $dbRow['date_start'];
        $this->date_end = $dbRow['date_end'];
    }

    /**
     * @return int (id)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return String (username)
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return date (start date)
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * @return date (end date)
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }
}