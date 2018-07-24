<?php

namespace App\Exception\Blog;

class PostAlreadyExistsException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}