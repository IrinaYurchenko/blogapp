<?php
namespace App\Exception\Blog;

class BlogAlreadyExistsException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}