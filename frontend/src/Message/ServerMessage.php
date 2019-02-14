<?php

namespace App\Message;

class ServerMessage
{
    private $id;
    private $action;

    public function __construct($action, $id = null)
    {
        $this->id = $id;
        $this->action = $action;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAction()
    {
        return $this->action;
    }
}
