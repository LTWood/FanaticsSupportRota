<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');
require_once ('Models/DevelopmentTeamDataSet.php');
require_once ("Models/UnavailabilityDataSet.php");
require_once("Models/AuditLogDataSet.php");

$getUsers = new UserDataSet();
$addUnavailability = new UnavailabilityDataSet();

if (isset($_POST["createTeamSubmit"]))
{
    if ($_POST["teamName"] == "")
    {
        $view->message = "Please enter a valid team name";
    }
    else
    {
        $addTeam = new DevelopmentTeamDataSet();
        $addTeam->addDevTeam($_POST["teamName"]);
        $view->message = "Added team called " . $_POST["teamName"];

        //audit log message for when a new team is created
        $supportTeamObject = new SupportTeamDataSet();
        $message = "".$_SESSION['user']." added team called ".$_GET['teamName']." at ".date("H:i:s")." On ".date("d/m/Y");
        $auditLogObject->addAuditLog($message);
    }
}

if(isset($_POST["unavailabilitySubmit"]))
{
    if (($_POST["startDate"]) == "")
    {
        $view->message = "Please enter Start Date";
    }
    elseif ($_POST["endDate"] == "")
    {
        $view->message = "Please enter End Date";
    }
    else
    {
        $view->allUsers = $getUsers->getUserByTeam($_POST['selectedTeam']);
        foreach ($view->allUsers as $users) {
            $addUnavailability->addUnavailability($users->getUsername(), $_POST["startDate"], $_POST["endDate"]);
            $view->message = "Unavailability updated for Team " . $_POST['selectedTeam']. " - " . $_POST["startDate"] . "---" . $_POST["endDate"];
        }


    }
}

if(isset($_POST["teamSchedule"]))
{
    $view->teamSchedule=$_POST["teamSchedule"];
    $getSchedule = new UnavailabilityDataSet();
    $view->teamUnavailability = $getSchedule->getTeamUnavailability($_POST["teamSchedule"]);
}

$getTeams = new DevelopmentTeamDataSet();
$view->teams = $getTeams->getDevelopmentTeams();

require_once('Views/teamManagement.phtml');