<?php
if(!isset($_SESSION))
{
    session_start();
}
$view = new stdClass();
if(isset($_POST["changePassword"]))
{
    require_once("Models/UserDataSet.php");
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
            $changePassword = new UserDataSet(); // Create new UserDataSet to use change password function
            $changePassword->changePassword(password_hash($_POST["password"], PASSWORD_DEFAULT), $_SESSION["user"]);
            //Calls changePassword with the hashed password entered and the username stored in the Session
            $view->message = "Password changed";
        }
}
require_once("Views/changePassword.phtml");