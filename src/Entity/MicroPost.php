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
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     *
     * @var \App\Entity\User[]|null
     */
    private $likedBy;

    /**
     * @ORM\Column(type="string", length=280)
     *
     * @var string|null
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime|null
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var \App\Entity\User|null
     */
    private $user;

    /**
     * MicroPost constructor.
     */
    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return \App\Entity\MicroPost
     */
    public function like(User $user): self
    {
        if ($this->likedBy->contains($user) === false) {
            $this->likedBy->add($user);
        }

        return $this;
    }

    /**
     * @param string|null $text
     *
     * @return \App\Entity\MicroPost
     */
    public function setText(?string $text = null): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param \DateTimeInterface|null $time
     *
     * @return \App\Entity\MicroPost
     */
    public function setTime(?\DateTimeInterface $time = null): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Sets time before persisting.
     *
     * @ORM\PrePersist()
     *
     * @throws \Exception
     */
    public function setTimeOnPersist(): void
    {
        $this->time = new \DateTime();
    }

    /**
     * @param \App\Entity\User|null $user
     *
     * @return self
     */
    public function setUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
