<?php
if (!isset($_SESSION))
{
    session_start();
}


$view = new stdClass();
$view->pageTitle = 'Support Rota';

require_once ('Models/UserDataSet.php');

if (isset($_POST['signIn'])) {
    if (isset($_POST['username']) && isset($_POST['psw'])) {
        $login = new UserDataSet();
        if ($login->login($_POST['username'], $_POST['psw'])) {
            $_SESSION['user'] = $_POST['username'];
            echo $_SERVER['PHP_SELF'];
        }
    }
}
if (isset($_POST["logout"]))
{
    unset($_SESSION["user"]);
}

require_once('Views/index.phtml');