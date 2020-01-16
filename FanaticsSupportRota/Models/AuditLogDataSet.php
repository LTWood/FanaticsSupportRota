<?php

require_once ('Models/Database.php');
require_once ('Models/AuditLog.php');

class AuditLogDataSet
{
    protected $_dbHandle, $dbInstance;
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //add Audit record to DB
    public function addAuditLog($message)
    {
        $sqlQuery = 'INSERT INTO audit_log (message) VALUES (?)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$message]);
    }

    //fetch all the audit logs
    public function getAuditLog()
    {
        $sqlQuery = 'SELECT * FROM audit_log';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = [];
        while($row = $statement->fetch()){
            $dataSet[] = new AuditLog($row);
        }
        return $dataSet; //return the array

    }

    public function removeAuditLog($id)
    {
        $sqlQuery = 'DELETE FROM audit_log WHERE id = '.$id;
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }
}