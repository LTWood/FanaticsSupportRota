<?php
if (!isset($_SESSION))
{
    session_start();
}
$view = new stdClass();
require_once ("Models/SupportTeamDataSet.php");
$userRota = new SupportTeamDataSet();
$view->rota = $userRota->findUserRota($_SESSION["user"]); //Gets all support dates for the user logged in
require_once("Views/user.phtml");
