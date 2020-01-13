<?php

//Incorporating the 'Database' and 'SupportTeam' classes
require_once ('Models/Database.php');
require_once ('Models/Unavailability.php');


/**
 * class that adds staff to unavailability
 */

class UnavailabilityDataSet
{

    //establish DB connection
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //created a new unavailability record
    public function addUnavailability($username, $date_start, $date_end)
    {
        $sqlQuery = 'INSERT INTO unavailability (username, date_start, date_end) VALUES (?,?,?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username, $date_start, $date_end]);
    }

    //removes developer from unavailability record
    public function removeUnavailability($id)
    {
        $sqlQuery = 'DELETE FROM unavailability WHERE id = ? ';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);
    }

//    public function getUnavailability($date_start, $date_end)
//    {
//        $sqlQuery = 'SELECT * FROM unavailability WHERE date_start = ? AND  date_end = ?';
//        $statement = $this->_dbHandle->prepare($sqlQuery);
//        $statement->execute([$date_start, $date_end]);
//    }




}