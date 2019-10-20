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
    public const ALL_ACTIONS = [
        self::EDIT,
        self::DELETE
    ];

    public const DELETE = 'delete';

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
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
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
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
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
