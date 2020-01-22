<?php

require_once ('Models/Database.php');
require_once ('Models/UserDataSet.php');
require_once ('Models/SupportTeamDataSet.php');
require_once ('Models/UnavailabilityDataSet.php');
require_once ('Models/AuditLogDataSet.php');

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
    public function generateRota($start_date, $weeks, $consecutiveLimit)
    {
        $supportTeamObject = new SupportTeamDataSet();

        if (($weeks % 2) != 0) {
            $weeks++;
        }

        //Remove old support teams that may overlap with generated ones
        $this->removeRotaSupportTeams($weeks);

        //Creates all the dates for the support teams (two week intervals)
        $dates = [];
        for ($i = 0; $i < $weeks; $i += 2) {
            array_push($dates, date('Y-m-d', strtotime($start_date ." ". ($i + 1) . " monday")));
            array_push($dates, date('Y-m-d', strtotime($start_date ." ". ($i + 2) . " sunday")));
        }
//        var_dump($dates);

        //Get dev pairs
        $devPairs = $this->generateDevPairs($weeks, $dates, $consecutiveLimit);

        //Creates a support team using the dev pair and the dates
        $datesIndex = 0;
        for($i=0;$i<($weeks/2);$i++){
            $supportTeamObject->addSupportTeam($dates[$datesIndex], $dates[$datesIndex + 1], $devPairs[$datesIndex], $devPairs[$datesIndex+1]); //Creates the support team
            $datesIndex = $datesIndex + 2;
        }
        //Add a audit message for the generation
        $auditLogObject = new AuditLogDataSet();
        $message = "".$_SESSION['user']." Generated a new support rota for ".$weeks." weeks at ".date("H:i:s")." On ".date("d/m/Y");
        $auditLogObject->addAuditLog($message);
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

        //Generates random pairs of devs
        $devPairs = [];
        for ($i = 0; $i < $weeks; $i += 2) {


                //If theres a junior dev not matched with a senior then generate two new devs
                $junior = false;
                while(!$junior) {
                    $junior = true;

                    //Add while loop that repeats selection of users if theyre not from different dev teams
                    $difTeams = false;
                    while (!$difTeams) {
                        $difTeams = true;

                        //If a dev is not available on the date assigned then generate two new devs
                        $valid = false;
                        while ($valid == false) {
                            $valid = true;

                            $user1 = mt_rand(0, count($users) - 1);
                            $user2 = mt_rand(0, count($users) - 1);
                            while ($user2 == $user1) { //If the same two people match then select another dev
                                $user2 = mt_rand(0, count($users) - 1);
                            }


                            //Checking availability of devs for the support team they will be assigned to
                            if (!$unavailabilityObject->checkAvailability($users[$user1]->getUsername(), $dates[$i], $dates[$i + 1])) {
                                $valid = false;
                            }
                            if (!$unavailabilityObject->checkAvailability($users[$user2]->getUsername(), $dates[$i], $dates[$i + 1])) {
                                $valid = false;
                            }
                        }
                        //CLOSE }

                        $team1 = $users[$user1]->getDevTeam();
                        $team2 = $users[$user2]->getDevTeam();
                        if ($team1 == $team2) {
                            $difTeams = false;
                        }
                    }

                    $dev1 = $users[$user1];
                    $dev2 = $users[$user2];
                    //Compares the experience of each dev (Junior should be paired with a Senior
                    if (($dev1->getExperience() == 'Junior') && ($dev2->getExperience() != 'Senior')) {
                        $junior = false;
                    }
                    if (($dev2->getExperience() == 'Junior') && ($dev1->getExperience() != 'Senior')) {
                        $junior = false;
                    }

                }

            array_push($devPairs, $dev1->getUsername()); //add devs to array of devs
            array_push($devPairs, $dev2->getUsername());
        }

        //Calculating amount of consecutive support team assignments for each dev.
        $consecutiveDevs = [];
        for ($i = 0; $i < count($devPairs); $i++) {
            $consecutiveDevs[$devPairs[$i]] = 0;
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
            if($consecutiveDevs[$devPairs[$i]] > $consecutiveLimit){
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