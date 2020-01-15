<?php
session_start();
require_once("Models/SupportTeamDataSet.php");
require_once("Models/AuditLogDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
//Pulling modified support team data
$data = $_POST['rota'];
$auditLogObject = new AuditLogDataSet();
foreach ($data as $supportWeek)
{
    $date = str_replace("/", "-", $supportWeek[0]);
    $supportTeam = $supportTeamObject->getSpecificTeam(date("Y-m-d", strtotime($date))); //grabs a specific support team to compare with from the DB
    if($supportTeam->getDeveloper1() != $supportWeek[1]){ //If there is a difference then create an audit message (change has been made)
        $message = "".$_SESSION['user']. " changed developer in support team (".date('d/m/Y',strtotime($supportTeam->getDateStart()))." --- ".date('d/m/Y',strtotime($supportTeam->getDateEnd())).") replaced '".trim($supportTeam->getDeveloper1())."' with '".trim($supportWeek[1])."' at ".date("H:i:s")." On ".date("d/m/Y");
        $auditLogObject->addAuditLog($message);
    }
    if($supportTeam->getDeveloper2() != $supportWeek[2]) //checking second developer in support team
    {
        $message = "".$_SESSION['user']. " changed developer in support team (".date('d/m/Y',strtotime($supportTeam->getDateStart()))." --- ".date('d/m/Y',strtotime($supportTeam->getDateEnd())).") replaced '".trim($supportTeam->getDeveloper2())."' with '".trim($supportWeek[2])."' at ".date("H:i:s")." On ".date("d/m/Y");
        $auditLogObject->addAuditLog($message);
    }
    //Update DB records
    $supportTeamObject->updateSlot($supportWeek[1], $supportWeek[2], date("Y-m-d", strtotime($date)));
}