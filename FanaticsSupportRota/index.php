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
    $rotaObject->generateRota(8);
}

$view->supportRota = $supportTeamObject->getSupportTeams(8);



if (isset($_POST['signIn'])) {
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $login = new UserDataSet();
        if ($login->login($_POST['username'], $_POST['psw'])) {
            $_SESSION['user'] = $_POST['username'];
        }
    }
}
if (isset($_POST["logout"]))
{
    unset($_SESSION["user"]);
}

require_once('Views/index.phtml');