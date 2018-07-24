<?php

namespace App\Manager\Blog;


use App\Entity\Blog\Blog;
use App\Entity\Blog\Post;

interface PostServiceInterface
{
    public function create(Post $post, Blog $blog): Post;
    public function getAll(): array;
    public function getPostById(int $postId): Post;
    public function increaseViews($postId);
}