<?php

//Incorporating the 'Database' and 'SupportTeam' classes
require_once ('Models/Database.php');
require_once ('Models/SupportTeam.php');

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

    //Calculate date range for x amount of weeks and fetch all support teams within that range
    public function getSupportTeams($weeks){
        $firstDay = date('Y-m-d', strtotime("monday -1 week"));
        $lastDay = date('Y-m-d', strtotime("sunday ".--$weeks." week"));
        $sqlQuery = "SELECT * FROM support_team WHERE (date_end > ?) AND (date_start < ?) ORDER BY date_start;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$firstDay, $lastDay]);
        $dataSet = [];
        while($row = $statement->fetch()){
            $dataSet[] = new SupportTeam($row); //Store DB row in a SupportTeam object that is then stored in an array of support teams
        }
        return $dataSet; //return array of support teams
    }

    // Fetch support teams within the range specified
    public function getSupportTeamsFromRange($firstDay, $lastDay)
    {
        $sqlQuery = "SELECT * FROM support_team WHERE (date_end >= ?) AND (date_start <= ?) ORDER BY date_start";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$firstDay, $lastDay]);
        $dataSet = [];
        while ($row = $statement->fetch())
        {
            array_push($dataSet, new SupportTeam($row));
        }
        return $dataSet; //return array of support teams
    }

    //Grabs the team with a specified start date
    public function getSpecificTeam($date_start)
    {
        $sqlQuery = 'SELECT * FROM support_team WHERE date_start = ? ';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$date_start]);
        return new SupportTeam($statement->fetch());
    }

    //Creates a new support team from user input
    public function addSupportTeam($date_start, $date_end, $developer_1, $developer_2){
        $sqlQuery = 'INSERT INTO support_team (date_start, date_end, developer_1, developer_2) VALUES (?,?,?,?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$date_start, $date_end, $developer_1, $developer_2]);
    }

    //Removes all support teams that are in the passed date range
    public function removeSupportTeamDateRange($date_start, $date_end){
        $sqlQuery = 'DELETE FROM support_team WHERE date_start >= "'.$date_start.'" AND date_end <= "'.$date_end.'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //Removes all support teams that are older than a specific date
    public function removeOldSupportTeam($date_end){
        $sqlQuery = 'DELETE FROM support_team WHERE date_end < ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$date_end]);
    }

    /*
     * findUserRota selects all the rotas from a single user using the username they are logged in with
     */
    public function findUserRota($username)
    {
        $sqlQuery = "SELECT * FROM support_team WHERE developer_1 = ? OR developer_2 = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username,$username]);
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new SupportTeam($row);
        }
        return $dataSet;
    }

    // Update a developer in support team
    public function updateSlot($developer1, $developer2, $startDate)
    {
        $sqlQuery = "UPDATE support_team SET developer_1 = ? WHERE date_start = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$developer1, $startDate]);
        $sqlQuery = "UPDATE support_team SET developer_2 = ? WHERE date_start = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$developer2, $startDate]);
    }
}