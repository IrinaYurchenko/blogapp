<?php

namespace App\Controller\Blog;

use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use App\Exception\Blog\BlogNotFoundException;
use App\Exception\Blog\PostAlreadyExistsException;
use App\Form\Blog\CommentType;
use App\Form\Blog\PostType;
use App\Manager\Blog\BlogManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    private $blogManager;

    public function __construct(BlogManagerInterface $blogManager)
    {
        $this->blogManager = $blogManager;
    }

    /**
     * @Route("/blog/{blogId}/post", name="blog_post")
     */
    public function create(Request $request, $blogId)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $this->blogManager->createPost($post, $blogId);
                return $this->redirectToRoute('blog_posts', [
                    'blogId' => $blogId
                ]);
            } catch (PostAlreadyExistsException $exception) {
                return $this->render('common/error.html.twig', [
                    'error_message' => $exception->getMessage()
                ]);
            }
        }

        return $this->render('blog/post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/{blogId}/posts", name="blog_posts")
     * @param $blogId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPosts($blogId)
    {
        try {
            $blog = $this->blogManager->getBlogById($blogId);
            $posts = $blog->getPosts();
            return $this->render('blog/post/index.html.twig', [
                'posts' => $posts
            ]);
        } catch (BlogNotFoundException $blogNotFoundException) {
            return $this->render('common/error.html.twig',
                ['error_message' => $blogNotFoundException->getMessage()]
            );
        }
    }

    /**
     * @Route("/post/{postId}", name="get_post")
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

            return $this->render('blog/post/index.html.twig', [
                'post' => $post,
                'comments' => $comments
            ]);
        } catch (BlogNotFoundException $blogNotFoundException) {
            return $this->render('common/error.html.twig',
                ['error_message' => $blogNotFoundException->getMessage()]
            );
        }
    }

    /**
     * @Route("/post/comment/{postId}", name="add_comment")
     * @param Request $request
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addComment(Request $request, $postId)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('add_comment', ['postId' => $postId]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $post = $this->blogManager->getPostById($postId);
            $this->blogManager->addComment($comment, $post);
            return $this->redirectToRoute('get_post', [
                'postId' => $postId
            ]);
        } else {
            return $this->render('blog/blog/addComment.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
