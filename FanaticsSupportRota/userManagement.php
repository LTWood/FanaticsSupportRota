<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');
require_once ('Models/DevelopmentTeamDataSet.php');
require_once ("Models/UnavailabilityDataSet.php");

/*
 * Checks to see if the submit button has been pressed. If it has, and both team and username are set, it calls
 * createUser from the userDataSet.
 */
$users = new UserDataSet();
$getTeams = new DevelopmentTeamDataSet();

if (isset($_POST['createUserSubmit']))
{
    if (isset($_POST['username']))
    {
        $login = new UserDataSet();
        $view->message = $login->createUser($_POST['username'], $_POST['selectedTeam'], $_POST['selectedDev'], $_POST['selectedExp']);
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
            $addUnavailability->addUnavailability($_POST["selectedUser"], $startDate, $endDate);
            $view->message = "Unavailability updated for " . $_POST["selectedUser"] . " - " . $_POST["startDate"] . "---" . $_POST["endDate"];
        }
}

if(isset($_GET["username"]))
{
    $getSchedule = new UnavailabilityDataSet();
    $view->unavailability = $getSchedule->getUnavailability($_GET["username"]);
}

if (isset($_GET['usernameDetails'])) {

    $view->userDetails = $users->getUserDetails($_GET['usernameDetails']);
}

if(isset($_POST["delete"]))
{
    $deleteUnavailability = new UnavailabilityDataSet();
    $deleteUnavailability->removeUnavailability($_POST["delete"]);
    $getSchedule = new UnavailabilityDataSet();
    $view->unavailability = $getSchedule->getUnavailability($_GET["username"]);
}


$view->users = $users->getAllUsers();


$view->teams = $getTeams->getDevelopmentTeams();


require_once('Views/userManagement.phtml');