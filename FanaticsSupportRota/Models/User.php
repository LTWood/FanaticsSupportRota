<?php


class User
{
    /*
     * Sets value for a single user from a single database row given
     */
    public function __construct($row)
    {
        $this->username = $row["username"];
        $this->DevTeam = $row["development_team"];
    }

    /*
     * 2 getters
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