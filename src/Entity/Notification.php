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
     */
    private $seen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->seen = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     *
     * @param mixed $seen
     *
     * @return static
     */
    public function setSeen($seen = null): self
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     *
     *
     * @param mixed $user
     *
     * @return static
     */
    public function setUser($user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
