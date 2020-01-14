<?php
session_start();

require_once ('Models/AuditLogDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Audit Log';

$auditLogObject = new AuditLogDataSet;

if(isset($_SESSION['user']) && $_SESSION['type']=='admin'){
    if(isset($_GET['Del'])){
        $auditLogObject->removeAuditLog($_GET['Del']);
    }
}



$view->auditLog = $auditLogObject->getAuditLog();



require_once ('Views/auditLog.phtml');