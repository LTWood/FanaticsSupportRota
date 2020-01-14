<?php
session_start();


$view = new stdClass();
$view->pageTitle = 'Support Rota';

require_once ('Models/UserDataSet.php');
require_once ('Models/SupportTeamDataSet.php');
require_once ('Models/GenerateRota.php');

$supportTeamObject = new SupportTeamDataSet();


//Generates a new rota for x amount of weeks (starting the beginning of the current week)
if(isset($_POST['generateRota'])){
    $rotaObject = new GenerateRota();
    $rotaObject->generateRota(16, 3);
}

$view->supportRota = $supportTeamObject->getSupportTeams(16);


if (isset($_POST['signIn'])) {
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $login = new UserDataSet();
        if ($login->login($_POST['username'], $_POST['psw'])) {
            $_SESSION['user'] = $_POST['username'];
            $_SESSION['type'] = $login->getUserType($_SESSION['user']);
        }
    }
}
if (isset($_POST["logout"]))
{
    unset($_SESSION["user"]);
    unset($_SESSION["type"]);
}

require_once('Views/index.phtml');