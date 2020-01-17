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

//Generates a new rota for x amount of weeks (starting the beginning of the current week)
if (isset($_POST['generateRota']) && isset($_POST['noWeeksGen'])) {
    $rotaObject = new GenerateRota();
    $rotaObject->generateRota($_POST['noWeeksGen'], 3);
}
//$view->developerTeamObject = new DevelopmentTeamDataSet();
//$view->developerTeams = $view->developerTeamObject->getDevelopmentTeams();
$view->unavailabilityObject = new UnavailabilityDataSet();

require_once("Views/maintenance.phtml");
