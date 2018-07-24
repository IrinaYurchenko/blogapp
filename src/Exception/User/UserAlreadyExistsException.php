<?php
namespace App\Exception\User;

class UserAlreadyExistsException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}