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

    public function checkAvailability($username, $date_start, $date_end){
        $sqlQuery = 'SELECT * FROM unavailability WHERE username = ? AND (NOT(? > date_end) && NOT(? < date_start))';
        //$sqlQuery ="SELECT * FROM unavailability WHERE username = ? AND (date_start <= ? OR date_end >= ?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username, $date_start, $date_end]);
        $dataSet = [];
//        $row = $statement->fetch();
//        var_dump($row);
        while ($row = $statement->fetch()) {
            $dataSet[] = new Unavailability($row);
        }
        if(count($dataSet)!= 0){
            return false;
        }else{
            return true;
        }
    }

    public function getUnavailability($username)
    {
        $sqlQuery = "SELECT * FROM unavailability WHERE username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);
        $dataSet =[];
        while($row = $statement->fetch())
        {
            $dataSet[] = new Unavailability($row);
        }
        return $dataSet;
    }

    public function getTeamUnavailability($team){
        $sqlQuery = "SELECT * FROM unavailability, users WHERE unavailability.username = users.username AND users.development_team = ? ";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$team]);
        $dataSet =[];
        while($row = $statement->fetch())
        {
            $dataSet[] = new Unavailability($row);
        }
        return $dataSet;
    }
}