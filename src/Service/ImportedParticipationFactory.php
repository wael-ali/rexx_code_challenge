<?php

namespace App\Service;

//     "participation_id": "1",
//     "employee_name": "Reto Fanzen",
//     "employee_mail": "reto.fanzen@no-reply.rexx-systems.com",
//     "event_id": 1,
//     "event_name": "PHP 7 crash course",
//     "participation_fee": "0",
//     "event_date": "2019-09-04"

class ImportedParticipationFactory
{
    private $participation_id; 
    private $employee_name;
    private $employee_mail;
    private $event_id;
    private $event_name;
    private $participation_fee;
    private $event_date;

    public function setParticipationId($id)
    {
        $this->participation_id = (int)$id;
    }
    public function getParticipationId(): int
    {
        return $this->participation_id;
    }

    public function setParticipationFee($fee)
    {
        $this->participation_fee = (int)((float)$fee * 100);
    }
    public function getParticipationFee(): int
    {
        return $this->participation_fee;
    }

    public function setEmployeeName($name)
    {
        $this->employee_name = $name;
    }
    public function getEmployeeName(): string
    {
        return $this->employee_name;
    }

    public function setEmployeeMail($mail)
    {
        $this->employee_mail = $mail;
    }
    public function getEmployeeMail(): String
    {
        return $this->employee_mail;
    }

    public function setEventId($id)
    {
        $this->event_id = $id;
    }
    public function getEventId(): Int
    {
        return $this->event_id;
    }

    public function setEventName($name)
    {
        $this->event_name = $name;
    }
    public function getEventName(): string 
    {
        return $this->event_name;
    }

    public function setEventDate($date)
    {
        $this->event_date = $date;
    }
    public function getEventDate(): string
    {
        return $this->event_date;
    }

    public function isValidParticipation(): Bool
    {
        return !is_null($this->event_id) && !is_null($this->employee_mail);
    }
    
}
