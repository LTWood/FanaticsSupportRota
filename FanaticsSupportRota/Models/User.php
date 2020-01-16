<?php

class User
{
    private $username, $DevTeam, $experience, $type;

    /*
     * Sets value for a single user from a single database row given
     */
    public function __construct($row)
    {
        $this->username = $row["username"];
        $this->DevTeam = $row["development_team"];
        $this->experience = $row["experience"];
        $this->type = $row["type"];
    }

    /*
     * 4 accessor methods, to retrieve fields
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

    public function getType()
    {
        return $this->type;
    }
}