<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 * @Hateoas\Relation("post", href = "expr('/posts/' ~ object.getPostId())")
 */
class Comment
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     *
     * @Assert\NotBlank()
     */
    private $post;

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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Get post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return int|null
     *
     * @Serializer\VirtualProperty()
     */
    public function getPostId()
    {
        return null !== $this->post ? $this->post->getId() : null;
    }

    /**
     * Set post
     *
     * @param Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt

     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
