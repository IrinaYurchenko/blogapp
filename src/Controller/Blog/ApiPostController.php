<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 27.06.18
 * Time: 21:14
 */

namespace App\Controller\Blog;


use App\Controller\ApiController;
use App\Manager\Blog\BlogManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiPostController extends ApiController
{
    private $blogManager;

    public function __construct(BlogManagerInterface $blogManager)
    {
        $this->blogManager = $blogManager;
    }

    /**
     * @Route("/api/blog/{blogId}/posts", name="api_blog_posts")
     * @param $blogId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPosts($blogId)
    {
        try {
            $blog = $this->blogManager->getBlogById($blogId);
            $posts = $blog->getPosts();
            return $this->createResponse($posts);
        } catch (BlogNotFoundException $blogNotFoundException) {
            return $this->render('common/error.html.twig',
                ['error_message' => $blogNotFoundException->getMessage()]
            );
        }
    }

    /**
     * @Route("/api/post/{postId}", name="api_get_post")
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPostById($postId)
    {
        try {
            $post = $this->blogManager->getPostById($postId);

            $comments = $post->getComments();

            if (!$comments) {
                $comments = [];
            }

            return $this->createResponse($post);;
        } catch (BlogNotFoundException $blogNotFoundException) {
            return $this->render('common/error.html.twig',
                ['error_message' => $blogNotFoundException->getMessage()]
            );
        }
    }
}