<?php

class AuditLog
{

    private $id, $message;

    public function __construct($auditMessage)
    {
        $this->message = $auditMessage['message'];
        $this->id = $auditMessage['id'];
    }


    public function getID()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

}