<?php
session_start();

$view = new stdClass();
$view->pageTitle = 'Maintenance';

if (isset($_POST['b'])) {
    $login = new UserDataSet();
    $login->createUser($_POST['username'], $_POST['team']);
}

require_once('Views/maintenance.phtml');
