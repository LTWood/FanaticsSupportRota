<?php

class User
{
    private $username, $DevTeam;

    /*
     * Sets value for a single user from a single database row given
     */
    public function __construct($row)
    {
        $this->username = $row["username"];
        $this->DevTeam = $row["development_team"];
    }

    /*
     * 2 accessor methods, to retrieve fields
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getDevTeam()
    {
        return $this->DevTeam;
    }
}