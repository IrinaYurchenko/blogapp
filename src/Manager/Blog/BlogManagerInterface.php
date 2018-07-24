<?php

namespace App\Manager\Blog;


use App\Entity\Blog\Blog;
use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use App\Entity\User\User;

interface BlogManagerInterface
{
    /**
     * Create new blog.
     *
     * @param Blog $blog
     * @param User $user
     * @return int
     */
    public function create(Blog $blog, User $user): int;

    /**
     * Get blog by user id.
     *
     * @param int $user
     * @return array
     */
    public function getBlogsByUserId(int $user): array;

    /**
     * Get blog by id.
     *
     * @param int $id
     * @return Blog
     */
    public function getBlogById(int $id): Blog;

    /**
     * Get blog by title.
     *
     * @param string $title
     * @return Blog
     */
    public function getBlogByTitle(string $title): Blog;

    /**
     * Remove blog by id.
     *
     * @param int $blog
     * @param User $user
     */
    public function removeBlog(int $blogId, User $user): void;

    public function createPost(Post $post, $blogId): void;

    public function getPostsForBlogs();

    public function getPostById($postId): Post;

    public function addComment(Comment $comment, Post $post);
}