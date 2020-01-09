<?php
/**
 * Class for storing SupportTeam objects
 */

class SupportTeam
{
    //Private fields for storing support team data
    private $id, $date_start, $date_end, $developer_1, $developer_2;

    //Constructor initialising fields with data from DB
    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->date_start = $dbRow['date_start'];
        $this->date_end = $dbRow['date_end'];
        $this->developer_1 = $dbRow['developer_1'];
        $this->developer_2 = $dbRow['developer_2'];
    }

    /**
     * @return int (id)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return String (date)
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * @return String (date)
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * @return String (developer 1)
     */
    public function getDeveloper1()
    {
        return $this->developer_1;
    }

    /**
     * @return String (developer 2)
     */
    public function getDeveloper2()
    {
        return $this->developer_2;
    }
}