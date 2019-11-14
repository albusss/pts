<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     *
     * @var \App\Entity\User
     */
    private $likedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     *
     * @var \App\Entity\MicroPost
     */
    private $microPost;

    /**
     * @return \App\Entity\User|null
     */
    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    /**
     * @return \App\Entity\MicroPost|null
     */
    public function getMicroPost(): ?MicroPost
    {
        return $this->microPost;
    }

    /**
     * @param \App\Entity\User|null $likedBy
     *
     * @return self
     */
    public function setLikedBy(?User $likedBy = null): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }

    /**
     * @param \App\Entity\MicroPost|null $microPost
     *
     * @return self
     */
    public function setMicroPost(?MicroPost $microPost = null): self
    {
        $this->microPost = $microPost;

        return $this;
    }
}
