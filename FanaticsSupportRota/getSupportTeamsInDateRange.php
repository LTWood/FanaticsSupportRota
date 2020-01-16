<?php

require_once("Models/SupportTeamDataSet.php");
require_once("Models/UnavailabilityDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
$startDate = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['start'])));
$endDate = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['end'])));
$view->supportTeams = $supportTeamObject->getSupportTeamsFromRange($startDate, $endDate);
$view->unavailabilityObject = new UnavailabilityDataSet();
require_once("Views/template/getSupportTeamsInDateRange.phtml");