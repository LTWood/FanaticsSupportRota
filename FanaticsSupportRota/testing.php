<?php

require_once("Models/DevelopmentTeamDataSet.php");
require_once("Models/UnavailabilityDataSet.php");

$view->developerTeamObject = new DevelopmentTeamDataSet();
$view->developerTeams = $view->developerTeamObject->getDevelopmentTeams();
$view->unavailabilityObject = new UnavailabilityDataSet();
$view->startDate = $_POST['date'][0];
$view->endDate = $_POST['date'][1];

require_once("testing.phtml");