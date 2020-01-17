<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Maintenance';
require_once("Models/SupportTeamDataSet.php");
require_once("Models/DevelopmentTeamDataSet.php");
require_once("Models/UnavailabilityDataSet.php");
require_once('Models/GenerateRota.php');

$supportTeamObject = new SupportTeamDataSet();
//$view->supportTeams = $supportTeamObject->getSupportTeams(8);
if (isset($_POST['viewNoWeeks'])) {
    if ($_POST['noWeeksView'] % 2 != 0) {
        $_POST['noWeeksView']++;
    }

    $view->supportTeams = $supportTeamObject->getSupportTeams($_POST['noWeeksView']);
    $view->noWeeks = $_POST['noWeeksView'];
} else {
    $view->supportTeams = $supportTeamObject->getSupportTeams(8);
    $view->noWeeks = 8;
}

$view->required = null;
//Checking if there is a latest support team
$latestArray = $supportTeamObject->getSupportTeamsFromRange(date('Y-m-d', strtotime("monday -3 week")), date('Y-m-d', strtotime("sunday -1 week")));
if(count($latestArray) != 0){
    $latest = $latestArray[0];
    $view->required = false;
}else{
    $latest = null;
    $view->required = true;
}

//Generates a new rota for x amount of weeks (starting the beginning of the current week)
if (isset($_POST['generateRota']) && isset($_POST['noWeeksGen'])) {
    $rotaObject = new GenerateRota();
    if ($latest == null) {
        if(isset($_POST['genStartDate'])) {
//            $view->required = false;
            $newDate = date("d-m-Y", strtotime(str_replace("/", "-", $_POST['genStartDate'])));
            //calculate the monday of the entered dates week
            $date = $newDate;
            if (date("w", strtotime($date)) == 1) {
                $date = date("d-m-Y", strtotime($date . " + 1 days"));
//                echo $date;
            }
            $monday = date('d-m-Y', strtotime("last monday", strtotime($date)));

            $rotaObject->generateRota($monday, $_POST['noWeeksGen'], 3);
        }else{
//            $view->required = true;
        }
    }
    else{
//        $view->required = false;
        $start_Date = date('Y-m-d', strtotime(date('Y-m-d', strtotime('+1 day', strtotime($latest->getDateEnd())))));
        $rotaObject->generateRota($start_Date, $_POST['noWeeksGen'], 3);
    }
}
//$view->developerTeamObject = new DevelopmentTeamDataSet();
//$view->developerTeams = $view->developerTeamObject->getDevelopmentTeams();
$view->unavailabilityObject = new UnavailabilityDataSet();

require_once("Views/maintenance.phtml");
