<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');
require_once ("Models/UnavilabilityDataSet.php");

/*
 * Checks to see if the submit button has been pressed. If it has, and both team and username are set, it calls
 * createUser from the userDataSet.
 */

$getUsers = new userDataSet;
$view->users = $getUsers->getAllUsers();

if (isset($_POST['createUserSubmit']))
{
    if (isset($_POST['username']) && isset($_POST['team']))
    {
        $login = new UserDataSet();
        $view->message = $login->createUser($_POST['username'], $_POST['team']);
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
            $addUnavailability->addUnavailability($_POST["selectedUser"], $_POST["startDate"], $_POST["endDate"]);
            $view->message = "Unavailability updated for " . $_POST["selectedUser"]. $_POST["startDate"] . $_POST["endDate"];
        }
}

require_once('Views/createAcc.phtml');