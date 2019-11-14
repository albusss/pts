<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class MicroPostVoter extends Voter
{
    /**
     * @var string[]
     */
    public const ALL_ACTIONS = [
        self::EDIT,
        self::DELETE
    ];

    /**
     * @var string
     */
    public const DELETE = 'delete';

    /**
     * @var string
     */
    public const EDIT = 'edit';

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface
     */
    private $manager;

    /**
     * MicroPostVoter constructor.
     *
     * @param \Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface $manager
     */
    public function __construct(AccessDecisionManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (\in_array($attribute, self::ALL_ACTIONS, true) === false) {
            return false;
        }

        if ($subject instanceof MicroPost === false) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->manager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        $authUser = $token->getUser();

        if ($authUser instanceof User === false) {
            return false;
        }

        /** @var MicroPost $microPost */
        $microPost = $subject;

        return $microPost->getUser()->getId() === $authUser->getId();
    }
}
