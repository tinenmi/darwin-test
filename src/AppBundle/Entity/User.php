<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $passwordHash;

    /**
     * One User have many BlogRecords
     * @ORM\OneToMany(targetEntity="BlogRecord", mappedBy="user")
     */
    private $blog;

    public function __construct()
    {
        $this->blog = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function addBlogRecord(\AppBundle\Entity\BlogRecord $blogRecord)
    {
        $this->blog[] = $book;

        return $this;
    }

    public function removeBlogRecord(\AppBundle\Entity\BlogRecord $blogRecord)
    {
        $this->blog->removeElement($book);
    }

    public function getBlog()
    {
        return $this->blog;
    }
}
