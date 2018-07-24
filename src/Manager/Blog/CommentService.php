<?php

namespace App\Manager\Blog;


use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use Doctrine\ORM\EntityManagerInterface;

class CommentService implements CommentServiceInterface
{
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->commentRepository = $entityManager->getRepository('App:Blog\Comment');
    }

    public function addComment(Comment $comment, Post $post)
    {
        $post->addComment($comment);
        $comment->setPost($post);

        $this->commentRepository->save($comment);
    }

}