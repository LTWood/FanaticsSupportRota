<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Maintenance';
require_once("Models/SupportTeamDataSet.php");
require_once("Models/DevelopmentTeamDataSet.php");
require_once("Models/UnavailabilityDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
//$view->supportTeams = $supportTeamObject->getSupportTeams(8);
if (isset($_POST['viewNoWeeks'])) {
    if ($_POST['noWeeksView'] % 2 != 0) {
        $_POST['noWeeksView']++;
    }

    $view->supportTeams = $supportTeamObject->getSupportTeams($_POST['noWeeksView']);
    $view->noWeeks = $_POST['noWeeksView'];
} else {
    $view->supportTeams = $supportTeamObject->getSupportTeams(30);
    $view->noWeeks = 8;
}
//$view->developerTeamObject = new DevelopmentTeamDataSet();
//$view->developerTeams = $view->developerTeamObject->getDevelopmentTeams();
$view->unavailabilityObject = new UnavailabilityDataSet();

require_once("Views/maintenance.phtml");
