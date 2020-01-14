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
        $sqlQuery = 'SELECT * FROM support_team WHERE date_start >= "'.$firstDay.'" AND date_end <= "'.$lastDay.'" ORDER BY date_start';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = [];
        while($row = $statement->fetch()){
            $dataSet[] = new SupportTeam($row); //Store DB row in a SupportTeam object that is then stored in an array of support teams
        }
        return $dataSet; //return array of support teams
    }

    //Creates a new support team from user input
    public function addSupportTeam($date_start, $date_end, $developer_1, $developer_2){
        $sqlQuery = 'INSERT INTO support_team (date_start, date_end, developer_1, developer_2) VALUES (?,?,?,?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$date_start, $date_end, $developer_1, $developer_2]);
    }

    //Removes specified support team
    public function delSupportTeam($id){
        $sqlQuery = 'DELETE FROM support_team WHERE id = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$id]);
    }

    //Removes all support teams that are in the passed date range
    public function removeSupportTeamDateRange($date_start, $date_end){
        $sqlQuery = 'DELETE FROM support_team WHERE date_start >= "'.$date_start.'" AND date_end <= "'.$date_end.'"';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
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