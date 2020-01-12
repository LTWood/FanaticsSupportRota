<?php

include_once ('Models/Database.php');
include_once ('Models/UserDataSet.php');

class GenerateRota
{
    //Establish DB connection
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //Creates a rota for x number of weeks
    public function generateRota($weeks){
        $usersObject = new UserDataSet();
        $users[] = $usersObject->getAllUsers();
        echo $users;
    }
}