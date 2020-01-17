<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');
require_once ('Models/DevelopmentTeamDataSet.php');
require_once ("Models/UnavailabilityDataSet.php");
require_once ('Models/AuditLogDataSet.php');

/*
 * Checks to see if the submit button has been pressed. If it has, and both team and username are set, it calls
 * createUser from the userDataSet.
 */
$users = new UserDataSet();
$getTeams = new DevelopmentTeamDataSet();
$auditLogObject = new AuditLogDataSet();

if (isset($_POST['createUserSubmit']))
{
    if (isset($_POST['username']))
    {
        $login = new UserDataSet();
        $view->message = $login->createUser($_POST['username'], $_POST['selectedTeam'], $_POST['selectedDev'], $_POST['selectedExp']);

        //audit log when a new user is created
        $message = "".$_SESSION['user']." created user ".$_POST['username']." who is part of team ".$_POST['selectedTeam']." at ".date("H:i:s")." On ".date("d/m/Y");
        $auditLogObject->addAuditLog($message);
    }
}
if (isset($_POST["logout"])) //Checks to see if the user logs out.
{
    unset($_SESSION["user"]);
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
            $addUnavailability = new UnavailabilityDataSet();
            $startDate = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['startDate'])));
            $endDate = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['endDate'])));
            $addUnavailability->addUnavailability($_GET["username"], $startDate, $endDate);
            $view->message = "Unavailability updated for " . $_GET["username"] . " - " . $_POST["startDate"] . "---" . $_POST["endDate"];

            //audit log for when user is unavailable
            $message = "".$_SESSION['user']." added unavailability for ".$_GET['username']." between ".$_POST["startDate"] . "---" . $_POST["endDate"]." at ".date("H:i:s")." On ".date("d/m/Y");
            $auditLogObject->addAuditLog($message);
        }
}


if(isset($_POST["updateUser"]))
{
    $users->updateUserDetails($_POST["updatedDevTeam"],$_POST["updatedDevExp"],$_POST["updateUser"]);

    //audit log - users details updated
    $message = "".$_SESSION['user']." updated  ".$_GET['username']." at ".date("H:i:s")." On ".date("d/m/Y");
    $auditLogObject->addAuditLog($message);
}

if(isset($_POST["deleteUser"]))
{
    $users->deleteUser($_POST["deleteUser"]);

    //audit log - user record is deleted
    $message = "".$_SESSION['user']." deleted ".$_GET['username']." records at ".date("H:i:s")." On ".date("d/m/Y");
    $auditLogObject->addAuditLog($message);
    $_GET["username"] = "";
}

if(isset($_POST["delete"]))
{
    $deleteUnavailability = new UnavailabilityDataSet();
    $deleteUnavailability->removeUnavailability($_POST["delete"]);
    $getSchedule = new UnavailabilityDataSet();
    $view->unavailability = $getSchedule->getUnavailability($_GET["username"]);
}

if(isset($_GET["username"]))
{
    $getSchedule = new UnavailabilityDataSet();
    $view->unavailability = $getSchedule->getUnavailability($_GET["username"]);
    $view->userDetails = $users->getUserDetails($_GET['username']);
}

$view->users = $users->getAllUsers();
$view->teams = $getTeams->getDevelopmentTeams();

require_once('Views/userManagement.phtml');