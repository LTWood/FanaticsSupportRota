<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');

/*
 * Checks to see if the submit button has been pressed. If it has, and both team and username are set, it calls
 * createUser from the userDataSet.
 */
if (isset($_POST['submit']))
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

require_once('Views/createAcc.phtml');