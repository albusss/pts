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
     */
    private $likedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $microPost;

    /**
     *
     *
     * @return mixed
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getMicroPost()
    {
        return $this->microPost;
    }

    /**
     *
     *
     * @param mixed $likedBy
     *
     * @return self
     */
    public function setLikedBy($likedBy = null): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }

    /**
     *
     *
     * @param mixed $microPost
     *
     * @return self
     */
    public function setMicroPost($microPost = null): self
    {
        $this->microPost = $microPost;

        return $this;
    }
}
