<?php
session_start();


$view = new stdClass();
$view->pageTitle = 'Support Rota';

require_once('Models/UserDataSet.php');
require_once('Models/SupportTeamDataSet.php');
require_once('Models/GenerateRota.php');
require_once('Models/UnavailabilityDataSet.php');


$supportTeamObject = new SupportTeamDataSet();
$unavailabilityObject = new UnavailabilityDataSet();

//Removes old support and unavailability records (set to -2 to leave one support team)
$unavailabilityObject->removeOldRecords(date('Y-m-d', strtotime("monday -1 week")));
$supportTeamObject->removeOldSupportTeam(date('Y-m-d', strtotime("monday -1 week")));


//Checking if there is a latest support team
$latest = $supportTeamObject->getLatestSupportTeam();
if($latest == null){
    echo "No entries";
}else{
    var_dump($latest);
}


//Generates a new rota for x amount of weeks (starting the beginning of the current week)
if (isset($_POST['generateRota']) && isset($_POST['noWeeksGen'])) {
    $rotaObject = new GenerateRota();
    $rotaObject->generateRota($_POST['noWeeksGen'], 3);
}

if (isset($_POST['viewNoWeeks'])) {
    if ($_POST['noWeeksView'] % 2 != 0) {
        $_POST['noWeeksView']++;
    }

    $view->supportRota = $supportTeamObject->getSupportTeams($_POST['noWeeksView']);
    $view->noWeeks = $_POST['noWeeksView'];
} else {
    $view->supportRota = $supportTeamObject->getSupportTeams(16);
    $view->noWeeks = 16;
}


if (isset($_POST['signIn'])) {
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $login = new UserDataSet();
        if ($login->login($_POST['username'], $_POST['psw'])) {
            $_SESSION['user'] = $_POST['username'];
            $_SESSION['type'] = $login->getUserType($_SESSION['user']);
        }
    }
}
if (isset($_POST["logout"])) {
    unset($_SESSION["user"]);
    unset($_SESSION["type"]);
}

require_once('Views/index.phtml');