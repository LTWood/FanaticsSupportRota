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

//Clears the entire audit log!
if(isset($_POST['clearLogBtn'])){
    $auditLogObject->clearAuditLog();
}

//Loads records in the audit log
$log = $auditLogObject->getAuditLog();
if(count($log) == 0){ //If the log is empty send a message to be displayed to the user
    $view->emptyMessage = "Log is empty!";
    $view->auditLog = $log;
}else{
    $view->emptyMessage = "";
    $view->auditLog = $log;
}

require_once ('Views/auditLog.phtml');