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
    public function generateRota($weeks)
    {
        $supportTeamObject = new SupportTeamDataSet();
        $usersObject = new UserDataSet();
        if (($weeks % 2) != 0) {
            $weeks++;
        }

        //Removes all support teams that already exist for the following weeks
        $start_range = date('Y-m-d', strtotime("monday -1 week"));
        $end_range = date('Y-m-d', strtotime("sunday " . ($weeks - 1) . " week"));
        $supportTeamObject->removeSupportTeamDateRange($start_range, $end_range);

        //Creates all the dates for the support teams (two week intervals)
        $dates = [];
        for ($i = 0; $i < $weeks; $i += 2) {
            array_push($dates, date('Y-m-d', strtotime("monday " . ($i - 1) . " week")));
            array_push($dates, date('Y-m-d', strtotime("sunday " . ($i + 1) . " week")));
        }

        //Grabs all the current devs
        $users = $usersObject->getAllUsers();
        //Generates random pairs of devs
        $devPairs = [];
        for ($i = 0; $i < $weeks; $i += 2) {
            $valid = false;
            while($valid == false){
                $valid = true;
                $v1 = mt_rand(0, count($users) - 1);
                $v2 = mt_rand(0, count($users) - 1);
                while ($v2 == $v1) { //If the same two people match then select another dev
                    $v2 = mt_rand(0, count($users) - 1);
                }
                $dev1 = $users[$v1]->getUsername();
                $dev2 = $users[$v2]->getUsername();
//                if()
            }

            array_push($devPairs, $dev1);
            array_push($devPairs, $dev2);
        }

        $consecutiveDevs = [];
        for ($i = 0; $i < count($devPairs); $i++) {
            $consecutiveDevs[$devPairs[$i]] = 1;
        }

        //Check for consecutive support teams
//        $dev1 = $devPairs[0];
//        $dev2 = $devPairs[1];
//        for($i=2;$i<$weeks;$i++){
//            if(($dev1 == $devPairs[$i]) || $dev1 == $devPairs[$i+1]){
//                $consecutiveDevs[$dev1]++;
//            }elseif(($dev2 == $devPairs[$i]) || $dev2 == $devPairs[$i+1]){
//
//            }
//        }
        //Array to loop through entire selected devs
        for ($i = 0; $i < count($devPairs); $i++) {
            if(($i % 2)!=0) {
                if(isset($devPairs[$i+1]) && (($devPairs[$i] == $devPairs[$i+1])||($devPairs[$i] == $devPairs[$i+2]))){
                    $consecutiveDevs[$devPairs[$i]]++;
                }
            }else{
                if(isset($devPairs[$i+2]) && (($devPairs[$i] == $devPairs[$i+2])|| ($devPairs[$i] == $devPairs[$i+3]))){
                    $consecutiveDevs[$devPairs[$i]]++;
                }
            }
        }

        var_dump($consecutiveDevs);


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