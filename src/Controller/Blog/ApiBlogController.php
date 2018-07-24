<?php

namespace App\Controller\Blog;


use App\Controller\ApiController;
use App\Manager\Blog\BlogManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiBlogController extends ApiController
{
    private $blogManager;

    public function __construct(BlogManagerInterface $blogManager)
    {
        $this->blogManager = $blogManager;
    }

    /**
     * @Route("/api", methods={"GET"}, name="api_resource")
     */
    public function index()
    {
        $posts = $this->blogManager->getPostsForBlogs();

        if (empty($posts)) {
            $posts = [];
        }

        return $this->createResponse($posts);
    }
}