<?php

namespace App\Exception\Blog;

class PostNotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}