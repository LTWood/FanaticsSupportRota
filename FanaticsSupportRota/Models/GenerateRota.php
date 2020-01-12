<?php

require_once ('Models/Database.php');
require_once ('Models/UserDataSet.php');
require_once ('Models/SupportTeamDataSet.php');
require_once ('Models/SupportTeam.php');

class GenerateRota
{
    //Establish DB connection
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //Creates a rota for x number of weeks
    public function generateRota($weeks){
        $supportTeamObject = new SupportTeamDataSet();
        $usersObject = new UserDataSet();
        if(($weeks % 2) != 0){
            $weeks ++;
        }

        //Removes all support teams that already exist for the following weeks
        $start_range = date('Y-m-d', strtotime("monday -1 week"));
        $end_range = date('Y-m-d', strtotime("sunday ".($weeks-1)." week"));
        $supportTeamObject->removeSupportTeamDateRange($start_range,$end_range);

        //Creates all the dates for the support teams (two week intervals)
        $dates = [];
        for($i = 0; $i < $weeks; $i+= 2){
            array_push($dates, date('Y-m-d', strtotime("monday ".($i-1)." week")));
            array_push($dates, date('Y-m-d', strtotime("sunday ".($i+1)." week")));
        }

        //Grabs all the current devs
        $users = $usersObject->getAllUsers();
        //Generates random pairs of devs
        $devPairs = [];
        for($i = 0; $i < $weeks; $i+= 2){
            $v1 = mt_rand(0,count($users)-1);
            $v2 = mt_rand(0,count($users)-1);
            while($v2 == $v1){ //If the same two people match then select another dev
                $v2 = mt_rand(0,count($users)-1);
            }
            $dev1 = $users[$v1]->getUsername();
            $dev2 = $users[$v2]->getUsername();
            array_push($devPairs, $dev1);
            array_push($devPairs, $dev2);
        }

        //Creates a support team using the dev pair and the dates
        $datesIndex = 0;
        for($i=0;$i<($weeks/2);$i++){
            $supportTeamObject->addSupportTeam($dates[$datesIndex], $dates[$datesIndex + 1], $devPairs[$datesIndex], $devPairs[$datesIndex+1]); //Creates the support team
            $datesIndex = $datesIndex + 2;
        }
    }
}
/**
─────▄██▀▀▀▀▀▀▀▀▀▀▀▀▀██▄─────
────███───────────────███────
───███─────────────────███───
──███───▄▀▀▄─────▄▀▀▄───███──
─████─▄▀────▀▄─▄▀────▀▄─████─
─████──▄████─────████▄──█████
█████─██▓▓▓██───██▓▓▓██─█████
█████─██▓█▓██───██▓█▓██─█████
█████─██▓▓▓█▀─▄─▀█▓▓▓██─█████
████▀──▀▀▀▀▀─▄█▄─▀▀▀▀▀──▀████
███─▄▀▀▀▄────███────▄▀▀▀▄─███
███──▄▀▄─█──█████──█─▄▀▄──███
███─█──█─█──█████──█─█──█─███
███─█─▀──█─▄█████▄─█──▀─█─███
███▄─▀▀▀▀──█─▀█▀─█──▀▀▀▀─▄███
████─────────────────────████
─███───▀█████████████▀───████
─███───────█─────█───────████
─████─────█───────█─────█████
───███▄──█────█────█──▄█████─
─────▀█████▄▄███▄▄█████▀─────
──────────█▄─────▄█──────────
──────────▄█─────█▄──────────
───────▄████─────████▄───────
─────▄███████───███████▄─────
───▄█████████████████████▄───
─▄███▀───███████████───▀███▄─
███▀─────███████████─────▀███
▌▌▌▌▒▒───███████████───▒▒▐▐▐▐
─────▒▒──███████████──▒▒─────
──────▒▒─███████████─▒▒──────
───────▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒───────
─────────████░░█████─────────
────────█████░░██████────────
──────███████░░███████───────
─────█████──█░░█──█████──────
─────█████──████──█████──────
──────████──████──████───────
──────████──████──████───────
──────████───██───████───────
──────████───██───████───────
──────████──████──████───────
─██────██───████───██─────██─
─██───████──████──████────██─
─███████████████████████████─
─██─────────████──────────██─
─██─────────████──────────██─
────────────████─────────────
─────────────██──────────────

 */