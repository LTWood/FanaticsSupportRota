<?php

class User
{
    private $username, $DevTeam, $experience;

    /*
     * Sets value for a single user from a single database row given
     */
    public function __construct($row)
    {
        $this->username = $row["username"];
        $this->DevTeam = $row["development_team"];
        $this->experience = $row["experience"];
    }

    /*
     * 3 accessor methods, to retrieve fields
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getDevTeam()
    {
        return $this->DevTeam;
    }

    public function getExperience()
    {
        return $this->experience;
    }
}