<?php

namespace App\Entity\Blog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Blog\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $views;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */

    private $likes;
    /**
     * @var Blog
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog\Blog" , inversedBy="posts")
     * @ORM\JoinColumn(name="blog_id" , referencedColumnName="id")
     */

    private $blog;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Blog\Comment" , mappedBy="post")
     */
    private $comments;

    public function __construct()
    {
        $this->createdAt=new \DateTime();
        $this->comments=new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }


    /**
     * @return int
     */
    public function getViews():int
    {
        return $this->views;
    }

    /**
     * @param int $views;
     * @return Post
     */
    public function setViews(int $views):Post
    {
        $this->views=$views;
        return $this;
    }

    /**
     * @return int
     */
    public function getLikes():int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     * @return Post
     */
    public function setLikes(int $likes):Post
    {
        $this->likes=$likes;
        return $this;
    }

    /**
     * @return Blog
     */
    public function getBlog():Blog
    {
        return $this->blog;
    }

    /**
     * @param Blog $blog
     * @return Post
     */
    public function setBlog(Blog $blog):Post
    {
        $this->blog=$blog;
        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        return $this;
    }
}
