<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"like" = "LikeNotification"})
 */
abstract class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $seen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     *
     * @var \App\Entity\User|null
     */
    private $user;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->seen = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool|null
     */
    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    /**
     * @return \App\Entity\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param bool|null $seen
     *
     * @return \App\Entity\Notification
     */
    public function setSeen(?bool $seen = null): self
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * @param \App\Entity\User|null $user
     *
     * @return \App\Entity\Notification
     */
    public function setUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
