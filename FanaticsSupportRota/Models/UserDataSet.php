<?php


class UserDataSet
{
    protected $_dbHandle, $_dbInstance;

    /*
     * Makes database connection
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /*
     * getAllUsers selects everything except passwords from users
     * @parameters : None
     * @return : Array, results from the select statement
     */
    public function getAllUsers()
    {
        $sqlQuery = "SELECT username, development_team FROM users";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        return $statement->fetch();
    }

    /*
     * login takes the username and password of a user, and compares the inputted password to the hashed password
     * stored the in the database
     * @parameters : $username - String, users username
     *               $password - String, users password to compare
     * @returns : Boolean - True if password correct, False if password wrong
     */
    public function login($username, $password)
    {
        $sqlQuery = "SELECT password FROM users WHERE username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        return (password_verify($password, $statement->execute([$username])["password"])); //Array in execute binds params for security

    }
}