<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 * @Hateoas\Relation("self", href = "expr('/posts/' ~ object.getId())")
 * @Hateoas\Relation("comments", href = "expr('/posts/' ~ object.getId() ~ '/comments/')")
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    private $body;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     *
     * @Serializer\Expose()
     */
    private $published;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     *
     * @Serializer\Expose()
     */
    private $tags;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->published = false;
        $this->tags      = new ArrayCollection();
        $this->comments  = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title

     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body

     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set published
     *
     * @param boolean $published

     */
    public function setPublished($published)
    {
        $this->published = (bool) $published;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Add tag
     *
     * @param Tag $tags

     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Get tags
     *
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param Collection $tags
     */
    public function setTags(Collection $tags)
    {
        $this->tags = new ArrayCollection();

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * Add comment
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $comment->setPost($this);
        $this->comments[] = $comment;
    }

    /**
     * Set comments
     *
     * @param Collection $comments
     */
    public function setComments(Collection $comments)
    {
        $this->comments = new ArrayCollection();

        foreach ($comments as $comment) {
            $this->addComment($comment);
        }
    }

    /**
     * Get comments
     *
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
