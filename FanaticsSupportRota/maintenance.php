<?php
session_start();

if (isset($_POST['b'])) {
    $login = new UserDataSet();
    $login->createUser($_POST['username'], $_POST['team']);
}

$view = new stdClass();
$view->pageTitle = 'Maintenance';
require_once("Models/SupportTeamDataSet.php");
require_once("Models/DevelopmentTeamDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
$view->supportTeams = $supportTeamObject->getSupportTeams(8);
$view->developerTeamObject = new DevelopmentTeamDataSet();
$view->developerTeams = $view->developerTeamObject->getDevelopmentTeams();
if (isset($_POST['b'])) {
    $login = new UserDataSet();
    $login->createUser($_POST['username'], $_POST['team']);
}

require_once("Views/maintenance.phtml");
