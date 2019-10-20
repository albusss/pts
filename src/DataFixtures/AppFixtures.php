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
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $microPost = new MicroPost();
            $microPost
                ->setText('Some text:' . \random_int(0, 100))
                ->setTime(new \DateTime())
                ->setUser($this->getReference('user'));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('albert')
            ->setFullName('albert')
            ->setEmail('albert@mail.ru')
            ->setPassword($this->encoder->encodePassword($user, '123'));

        $this->addReference('user', $user);
        $manager->persist($user);
        $manager->flush();
    }
}
