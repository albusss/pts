<?php
declare(strict_types=1);

namespace App\Service\Like;

use App\Entity\MicroPost;
use App\Entity\User;

interface LikeServiceInterface
{
    /**
     * Likes the Post.
     *
     * @param \App\Entity\User $currentUser
     * @param \App\Entity\MicroPost $post
     *
     * @return void
     */
    public function like(User $currentUser, MicroPost $post): void;

    /**
     * Unlikes the Post.
     *
     * @param \App\Entity\User $currentUser
     * @param \App\Entity\MicroPost $post
     *
     * @return void
     */
    public function unlike(User $currentUser, MicroPost $post): void;
}
