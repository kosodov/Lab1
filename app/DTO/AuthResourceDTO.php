<?php

namespace App\DTO;

class AuthResourceDTO
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
}