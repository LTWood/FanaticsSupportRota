<?php

require_once ('Models/Database.php');
require_once ('Models/UserDataSet.php');
require_once ('Models/SupportTeamDataSet.php');
require_once ('Models/UnavailabilityDataSet.php');

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
    public function generateRota($weeks, $consecutiveLimit)
    {
        $supportTeamObject = new SupportTeamDataSet();

        if (($weeks % 2) != 0) {
            $weeks++;
        }

        //Remove old support teams that may overlap with generated ones
//        $this->removeRotaSupportTeams($weeks);

        //Creates all the dates for the support teams (two week intervals)
        $dates = [];
        for ($i = 0; $i < $weeks; $i += 2) {
            array_push($dates, date('Y-m-d', strtotime("monday " . ($i - 1) . " week")));
            array_push($dates, date('Y-m-d', strtotime("sunday " . ($i + 1) . " week")));
        }

        //Get dev pairs
        $devPairs = $this->generateDevPairs($weeks, $dates, $consecutiveLimit);

        //Creates a support team using the dev pair and the dates
//        $datesIndex = 0;
//        for($i=0;$i<($weeks/2);$i++){
//            $supportTeamObject->addSupportTeam($dates[$datesIndex], $dates[$datesIndex + 1], $devPairs[$datesIndex], $devPairs[$datesIndex+1]); //Creates the support team
//            $datesIndex = $datesIndex + 2;
//        }
    }

    //Method for removing all support teams that will overlap with the rotas support teams
    private function removeRotaSupportTeams($weeks){
        $supportTeamObject = new SupportTeamDataSet();
        //Removes all support teams that already exist for the following weeks
        $start_range = date('Y-m-d', strtotime("monday -1 week"));
        $end_range = date('Y-m-d', strtotime("sunday " . ($weeks - 1) . " week"));
        $supportTeamObject->removeSupportTeamDateRange($start_range, $end_range);
    }

    //Method for generating the support dev pairs
    private function generateDevPairs($weeks, $dates, $consecutiveLimit){
        $unavailabilityObject = new UnavailabilityDataSet();
        $usersObject = new UserDataSet();

        //Grabs all the current devs
        $users = $usersObject->getAllUsers();


        $junior = false;
        while(!$junior) {
            $junior = true;

            //Add while loop that repeats selection of users if theyre not from different dev teams
            $difTeams = false;
            while(!$difTeams) {
                $difTeams = true;

                $user1 = mt_rand(0, count($users) - 1);
                $user2 = mt_rand(0, count($users) - 1);
                while ($user2 == $user1) { //If the same two people match then select another dev
                    $user2 = mt_rand(0, count($users) - 1);
                }

                $team1 = $users[$user1]->getDevTeam();
                $team2 = $users[$user2]->getDevTeam();
                if($team1 == $team2){
                    $difTeams = false;
//                    echo "TEAMS MATCH!!!";
                }
                else{
//                    echo $team1 ."  ". $team2. "<br>";
                }
            }

            //Testing ###REMOVE???###
            $username1 = $users[$user1]->getUsername();
            $username2 = $users[$user2]->getUsername();


            $user1 = $users[$user1]->getExperience();
            $user2 = $users[$user2]->getExperience();
            if(($user1 == 'junior')&&($user2 != 'Senior')){
                $junior = false;
            }
            if(($user2 == 'junior')&&($user1 != 'Senior')){
                $junior = false;
            }

        }
        //TESTING ###REMOVE###
        echo    $username1 . " XP: ".$user1 . " Team: ". $team1."<br>";
        echo    $username2 . " XP: ".$user2 . " Team: ". $team2."<br>";


        //Generates random pairs of devs
        $devPairs = [];
        for ($i = 0; $i < $weeks; $i += 2) {
            //If a dev is not available on the date assigned then generate two new devs
            $valid = false;
            while($valid == false){
                $valid = true;



                //Generating a random dev pair ###Change this if want to match a senior with a junior dev! ###
                $v1 = mt_rand(0, count($users) - 1);
                $v2 = mt_rand(0, count($users) - 1);
                while ($v2 == $v1) { //If the same two people match then select another dev
                    $v2 = mt_rand(0, count($users) - 1);
                }
                // ### ###





                $dev1 = $users[$v1]->getUsername(); //Get usernames for both devs
                $dev2 = $users[$v2]->getUsername();



                //Checking availability of devs for the support team they will be assigned to
                if(!$unavailabilityObject->checkAvailability($dev1, $dates[$i], $dates[$i+1])){
                    $valid = false;
                }
                if(!$unavailabilityObject->checkAvailability($dev2, $dates[$i], $dates[$i+1])){
                    $valid = false;
                }
            }
            array_push($devPairs, $dev1); //add devs to array of devs
            array_push($devPairs, $dev2);
        }

        //Calculating amount of consecutive support team assignments for each dev.
        $consecutiveDevs = [];
        for ($i = 0; $i < count($devPairs); $i++) {
            $consecutiveDevs[$devPairs[$i]] = 1;
        }

        //Array to loop through entire selected devs - If a consecutive match is found increment consecutive counter for that dev
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

        //Checking that no dev is over the limit for consecutive support team assignments
        $underLimit = true;
        for ($i = 0; $i < count($devPairs); $i++) {
            if($consecutiveDevs[$devPairs[$i]] >= $consecutiveLimit){
                $underLimit = false;
            }
        }
        //If over limit regenerate the dev pairings
        if(!$underLimit){
            return $this->generateDevPairs($weeks, $dates, $consecutiveLimit);
        }
        else{
            return $devPairs;
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