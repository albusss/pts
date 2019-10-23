<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $likedBy;

    /**
     * @ORM\Column(type="string", length=280)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * MicroPost constructor.
     */
    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function like(User $user)
    {
        if ($this->likedBy->contains($user) === false) {
            $this->likedBy->add($user);
        }
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     *
     * @ORM\PrePersist()
     *
     * @throws \Exception
     */
    public function setTimeOnPersist()
    {
        $this->time = new \DateTime();
    }

    /**
     *
     *
     * @param mixed $user
     *
     * @return self
     */
    public function setUser($user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
