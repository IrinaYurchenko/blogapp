<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Blog;
use App\Exception\Blog\BlogAlreadyExistsException;
use App\Exception\Blog\BlogNotFoundException;
use App\Form\Blog\BlogType;
use App\Manager\Blog\BlogManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    private $blogManager;

    public function __construct(BlogManagerInterface $blogManager)
    {
        $this->blogManager = $blogManager;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $posts = $this->blogManager->getPostsForBlogs();

        if (empty($posts)) {
            $posts = [];
        }

        return $this->render('blog/blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog", name="create_blog")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function create(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try {
                $user = $this->getUser();
                if (!$user) {
                    return $this->redirectToRoute('login_user');
                }
                $this->blogManager->create($blog, $user);

                return $this->redirectToRoute('homepage');
            } catch (BlogAlreadyExistsException $e) {
                return $this->render('common/error.html.twig', [
                    'error_message' => $e->getMessage()
                ]);
            }
        }

        return $this->render('blog/blog/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/{blogId}", name="posts")
     * @param $blogId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPosts($blogId)
    {
        try {
            $blog = $this->blogManager->getBlogById($blogId);
            $posts = $blog->getPosts();
            if (empty($posts)) {
                $posts = [];
            }
            return $this->render('blog/blog/blog_posts.html.twig', [
                'posts' => $posts
            ]);
        } catch (BlogNotFoundException $blogNotFoundException) {
            return $this->render('common/error.html.twig', [
                'error_message' => $blogNotFoundException->getMessage()
            ]);
        }
    }
}
