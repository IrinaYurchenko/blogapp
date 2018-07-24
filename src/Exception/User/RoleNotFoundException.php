<?php
namespace App\Exception\User;

class RoleNotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}