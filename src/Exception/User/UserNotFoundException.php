<?php
namespace App\Exception\User;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}