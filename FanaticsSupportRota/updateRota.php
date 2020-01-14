<?php
require_once("Models/SupportTeamDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
$data = $_POST['rota'];
foreach ($data as $supportWeek)
{
//    $startDate = date("Y-d-m", strtotime($supportWeek[0]));
    $supportTeamObject->updateSlot($supportWeek[1], $supportWeek[2], $supportWeek[0]);
    //$startDate = date("Y-m-d", strtotime($supportWeek[0]));
}