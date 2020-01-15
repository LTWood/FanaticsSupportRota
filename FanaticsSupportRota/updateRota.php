<?php
session_start();
require_once("Models/SupportTeamDataSet.php");
require_once("Models/AuditLogDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
$data = $_POST['rota'];
$auditLogObject = new AuditLogDataSet();
foreach ($data as $supportWeek)
{
    $date = str_replace("/", "-", $supportWeek[0]);
    $supportTeamObject->updateSlot($supportWeek[1], $supportWeek[2], date("Y-m-d", strtotime($date)));
    $supportTeam = $supportTeamObject->getSpecificTeam(date("Y-m-d", strtotime($supportWeek[0])));
    if($supportTeam->getDeveloper1() != $supportWeek[1]){
        $message = "".$_SESSION['user']. " changed developer in support team (".date('d-m-Y',strtotime($supportTeam->getDateStart()))." --- ".date('d-m-Y',strtotime($supportTeam->getDateEnd())).") replaced '".trim($supportTeam->getDeveloper1())."' with '".trim($supportWeek[1])."' at ".date("H:i:s")." On ".date("d-m-Y");
        $auditLogObject->addAuditLog($message);
    }
    if($supportTeam->getDeveloper2() != $supportWeek[2])
    {
        $message = "".$_SESSION['user']. " changed developer in support team (".date('d-m-Y',strtotime($supportTeam->getDateStart()))." --- ".date('d-m-Y',strtotime($supportTeam->getDateEnd())).") replaced '".trim($supportTeam->getDeveloper2())."' with '".trim($supportWeek[2])."' at ".date("H:i:s")." On ".date("d-m-Y");
        $auditLogObject->addAuditLog($message);
    }

//    $startDate = date("Y-d-m", strtotime($supportWeek[0]));
    $supportTeamObject->updateSlot($supportWeek[1], $supportWeek[2], date("Y-m-d", strtotime($supportWeek[0])));
}