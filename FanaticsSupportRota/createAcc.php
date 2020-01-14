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

$getUsers = new userDataSet;
$view->users = $getUsers->getAllUsers();

$getTeams = new DevelopmentTeamDataSet;
$view->teams = $getTeams->getDevelopmentTeams();

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
            $addUnavailability = new UnavailabilityDataSet();
            $addUnavailability->addUnavailability($_POST["selectedUser"], $_POST["startDate"], $_POST["endDate"]);
            $view->message = "Unavailability updated for " . $_POST["selectedUser"]. $_POST["startDate"] . $_POST["endDate"];
        }
}

require_once('Views/createAcc.phtml');