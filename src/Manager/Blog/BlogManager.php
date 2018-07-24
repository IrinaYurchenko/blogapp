<?php

namespace App\Manager\Blog;


use App\Entity\Blog\Blog;
use App\Entity\Blog\Comment;
use App\Entity\Blog\Post;
use App\Entity\User\User;
use App\Exception\Blog\BlogAlreadyExistsException;
use App\Exception\Blog\BlogNotFoundException;
use App\Exception\User\UserNotFoundException;
use App\Repository\Blog\BlogRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BlogManager implements BlogManagerInterface
{
    /**
     * @var BlogRepository
     */
    private $blogRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    private $postService;

    private $commentService;

    public function __construct(EntityManagerInterface $entityManager,
                                 CommentServiceInterface $commentService,
                                 PostServiceInterface $postService
    ) {
        $this->blogRepository = $entityManager->getRepository('App:Blog\Blog');
        $this->userRepository = $entityManager->getRepository('App:User\User');
        $this->postService = $postService;
        $this->commentService = $commentService;
    }

    /**
     * Create new blog.
     *
     * @param Blog $blog
     * @param User $user
     * @return int
     */
    public function create(Blog $blog, User $user): int
    {
        $isBlogExists = $this->blogRepository
            ->findBlogByUserIdAndTitle($blog->getTitle(), $user->getId());
        if ($isBlogExists) {
            throw new BlogAlreadyExistsException('Blog already exists');
        }

        $blog->setUser($user);
        $blogId = $this->blogRepository->save($blog);
        $user->addBlog($blog);
        return $blogId;
    }

    /**
     * Get blog by user id.
     *
     * @param int $user
     * @return array
     */
    public function getBlogsByUserId(int $userId): array
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        $userBlogs = $user->getBlogs();

        if (empty($userBlogs)) {
            return [];
        }

        return $userBlogs->toArray();
    }

    /**
     * Get blog by id.
     *
     * @param int $id
     * @return Blog
     */
    public function getBlogById(int $id): Blog
    {
        $blog = $this->blogRepository->find($id);
        if (!$blog) {
            throw new BlogNotFoundException('Blog not found');
        }

        return $blog;
    }

    /**
     * Get blog by title.
     *
     * @param string $title
     * @return Blog
     */
    public function getBlogByTitle(string $title): Blog
    {
        $blog = $this->blogRepository->findOneByTitle($title);
        if (!$blog) {
            throw new BlogNotFoundException('Blog not found');
        }

        return $blog;
    }

    /**
     * Remove blog by id.
     *
     * @param int $blog
     * @param User $user
     */
    public function removeBlog(int $blogId, User $user): void
    {
        $blog = $this->blogRepository->findBlogByUserId($user->getId(), $blogId);
        if (!$blog) {
            throw new BlogNotFoundException('Blog not found');
        }

        $this->blogRepository->remove($blog);
    }

    public function createPost(Post $post, $blogId): void
    {
        $blog = $this->getBlogById($blogId);

        $post = $this->postService->create($post, $blog);
    }

    public function getPostsForBlogs()
    {
        return $this->postService->getAll();
    }

    public function getPostById($postId): Post
    {
        return $this->postService->getPostById($postId);
    }

    public function addComment(Comment $comment, Post $post)
    {
        $this->commentService->addComment($comment, $post);
    }

}