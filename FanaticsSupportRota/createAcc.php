<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Create Account';

require_once ('Models/UserDataSet.php');

if (isset($_POST['submit'])) {
    if (isset($_POST['username']) && isset($_POST['team'])) {
        $login = new UserDataSet();
        $view->message = $login->createUser($_POST['username'], $_POST['team']);
    }
}
if (isset($_POST["logout"]))
{
    unset($_SESSION["user"]);
}

require_once('Views/createAcc.phtml');