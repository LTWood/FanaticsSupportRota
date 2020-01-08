<?php
/*
 * Class storing development team data
 */

class DevelopmentTeam
{
    //Team name field
    private $teamName;

    //Constructor that assigns the dev team name (from DB)
    public function __construct($data)
    {
        $this->teamName = $data['name'];
    }

    //Accessor method for the development teams name
    public function getTeamName()
    {
        return $this->teamName;
    }

}