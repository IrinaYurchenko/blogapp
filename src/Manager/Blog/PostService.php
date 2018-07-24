<?php

namespace App\Manager\Blog;


use App\Entity\Blog\Blog;
use App\Entity\Blog\Post;
use App\Exception\Blog\PostAlreadyExistsException;
use App\Exception\Blog\PostNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class PostService implements PostServiceInterface
{
    private $postRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->postRepository = $entityManager->getRepository('App:Blog\Post');
    }

    public function create(Post $post, Blog $blog): Post
    {
        $isPosts = $this->postRepository->findPostInBlogByTitle(
            $post->getTitle(),
            $blog->getId()
        );

        if ($isPosts) {
            throw new PostAlreadyExistsException(sprintf(
                'Post with titile `%s` already exists', $post->getTitle())
            );
        }

        $blog->addPost($post);

        $post->setBlog($blog);
        $this->postRepository->save($post);
        return $post;
    }

    public function getPostById(int $postId): Post
    {
        $post = $this->postRepository->find($postId);
        if (!$post) {
            throw new PostNotFoundException('Post not found');
        }
        $this->increaseViews($post);
        return $post;
    }

    public function getAll(): array
    {
        $posts = $this->postRepository->findAll();
        return $posts;
    }

    public function increaseViews($post)
    {
        $post->setViews($post->getViews() + 1);
        $this->postRepository->save($post);
    }

}