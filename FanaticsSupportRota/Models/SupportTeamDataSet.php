<?php

//Incorporating the 'Database' and 'SupportTeam' classes
include_once ('Models/Database.php');
include_once ('Models/SupportTeam.php');

/*
 * Class that maintains the interaction between the database and the client for the development team data
 */

class SupportTeamDataSet
{

    //Establish DB connection
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //Creates a new support team from user input
    public function addSupportTeam($date_start, $date_end, $developer_1, $developer_2){
        $sqlQuery = 'INSERT INTO support_team (date_start, date_end, developer_1, developer_2) VALUES ("?","?","?","?")';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$date_start, $date_end, $developer_1, $developer_2]);
    }

    //Removes specified support team
    public function delSupportTeam($id){
        $sqlQuery = 'DELETE FROM support_team WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);
    }

    //Adds a developer to a specific team if there is a position available
    public function addDevToSupportTeam($developer, $id){
        $sqlQuery = 'SELECT developer_1, developer_2 FROM support_team WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);
        $row = $statement->fetch();
        if(!empty($row['developer_1']) || !empty($row['developer_2'])){
            if(empty($row['developer_1'])){
                $sqlQuery = 'UPDATE support_team SET developer_1 = "?" WHERE id = ?';
            }else{
                $sqlQuery = 'UPDATE support_team SET developer_2 = "?" WHERE id = ?';
            }
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute([$developer, $id]);
        }
    }

    //Removes a specific developer from a specific support team
    public function removeDevFromSupportTeam($developer, $id){
        $sqlQuery = 'SELECT developer_1, developer_2 FROM support_team WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);
        $row = $statement->fetch();
        if($row['developer_1'] == $developer) {
            $sqlQuery = 'UPDATE support_team SET developer_1 = NULL WHERE id = ?';
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute([$id]);
        }elseif($row['developer_2'] == $developer){
            $sqlQuery = 'UPDATE support_team SET developer_2 = NULL WHERE id = ?';
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute([$id]);
        }
    }
}