<?php
session_start();


$view = new stdClass();
$view->pageTitle = 'Support Rota';

require_once ('Models/UserDataSet.php');
require_once ('Models/SupportTeamDataSet.php');

$supportTeamObject = new SupportTeamDataSet();
$view->supportRota = $supportTeamObject->getSupportTeams(8);

if (isset($_POST['signIn'])) {
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $login = new UserDataSet();
        if ($login->login($_POST['username'], $_POST['psw'])) {
            $_SESSION['user'] = $_POST['username'];
            echo $_SERVER['PHP_SELF'];
        }
    }
}
if (isset($_POST["logout"]))
{
    unset($_SESSION["user"]);
}

if(isset($_POST))

require_once('Views/index.phtml');