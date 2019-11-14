<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var string[]
     */
    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?',
    ];

    /**
     * @var mixed[]
     */
    private const USERS = [
        [
            'username' => 'john',
            'email' => 'john_doe@doe.com',
            'password' => '123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'rob',
            'email' => 'rob_smith@smith.com',
            'password' => '123',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'marry',
            'email' => 'marry_gold@gold.com',
            'password' => '123',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_ADMIN],
        ],
    ];

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadMicroPosts($manager);
    }

    /**
     * Loads Microposts.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     *
     * @throws \Exception
     */
    private function loadMicroPosts(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 30; $i++) {
            $date = new \DateTime();
            $date->modify('-' . \random_int(0, 10) . ' day');

            $randomPost = self::POST_TEXT[\random_int(0, \count(self::POST_TEXT) - 1)];
            $randomUser = $this->getReference(self::USERS[\random_int(0, \count(self::USERS) - 1)]['username']);

            $microPost = new MicroPost();
            $microPost
                ->setText($randomPost)
                ->setTime($date)
                ->setUser($randomUser);
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    /**
     * Loads User.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     */
    private function loadUser(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user
                ->setUsername($userData['username'])
                ->setFullName($userData['fullName'])
                ->setEmail($userData['email'])
                ->setRoles($userData['roles'])
                ->setPassword($this->encoder->encodePassword($user, $userData['password']))
                ->setEnabled(true);

            $this->addReference($userData['username'], $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
