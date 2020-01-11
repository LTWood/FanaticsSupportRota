<?php

require_once  ('Models/Database.php');
require_once ('Models/User.php');

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
        $this->generatePassword();
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
        $statement->execute([$username]);
        return (password_verify($password, $statement->fetch()["password"])); //Array in execute binds params for security
    }

    /*
     * createUser is the function that adds a new user to the users database. It takes the
     * username and initial team. It creates a password using the password creator method.
     * It checks if a username is unique using the checkUsername method
     * @parameters : $username - String; username inputted by admin when they create a user
     *               $team - The team user is assigned too on creation
     * @return : A message indicating the success of the operation
     */
    public function createUser($username, $team)
    {
        $password = $this->generatePassword();
        if ($this->checkUsername($username))
        {
            $sqlQuery = "INSERT INTO users VALUES (?,?,?)";
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute([$username, password_hash($password, PASSWORD_DEFAULT), $team]);
            return "User added; Password is: " .$password;
        }
        else
            {
                return "Username in use, try again";
            }
    }

    /*
     * generatePassword generates a random password by shuffling a set string and taking
     * the first 10 characters
     * @parameters : None
     * @returns : generated Password, String
     */
    private function generatePassword()
    {
        $permitted_chars = '023456789abcdefghijklmnpqrstuvwxyz';
        return(substr(str_shuffle($permitted_chars), 0, 10));
    }

    /*
     * checkUsername checks to see if the given username is already in the users
     * table
     * @parameters : username, String; username to check
     * @return : Boolean; True if fine, false if the username is already in use
     */
    private function checkUsername($username)
    {
        $sqlQuery = "SELECT username FROM users WHERE username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);
        return ($statement->fetch()["username"] == "");
    }

    /*
     * deleteUser deletes a user from the users table
     * @parameters : username, String; Target for deletion
     * @return : None
     */
    public function deleteUser($username)
    {
        $sqlQuery = "DELETE FROM users WHERE username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);
    }

    /*
     * changePassword changes the password of the target username to the given password
     * @parameters : password, String; The new password
     * @return : None
     */
    public function changePassword($password, $username)
    {
        $sqlQuery = "UPDATE users SET password = ? WHERE username = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$password, $username]);
    }
}