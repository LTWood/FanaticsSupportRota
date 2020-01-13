<?php
require_once("Models/SupportTeamDataSet.php");
$supportTeamObject = new SupportTeamDataSet();
$data = $_POST['rota'];
foreach ($data as $supportWeek)
{
    $supportTeamObject->updateSlot($supportWeek[1], $supportWeek[2], date("Y-d-m", strtotime($supportWeek[0])));
}