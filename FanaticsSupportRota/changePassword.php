<?php
if(!isset($_SESSION))
{
    session_start();
}
$view = new stdClass();
require_once("Models/UserDataSet.php");
if(isset($_POST["Confirm"]))
{
    if ($_POST["password"] == "")
    {
        $view->message = "Password cannot be empty";
    }
    elseif ($_POST["password"] != $_POST["confirmPassword"])
    {
        $view->message = "Passwords did not match";
    }
    else
        {
            $changePassword = new UserDataSet();
            $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $changePassword->changePassword($_POST["password"], $_SESSION["username"]);
            $view->message = "Password changed";
        }

}