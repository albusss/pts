<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"email"}, message="This email is already used")
 * @UniqueEntity(fields={"username"}, message="This username is already used")
 */
class User implements UserInterface, \Serializable
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="50", min="4")
     */
    private $fullName;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096", min="8")
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max="50", min="5")
     */
    private $username;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
    }

    /**
     *
     *
     * @return mixed
     */
    public function getPostsLiked()
    {
        return $this->postsLiked;
    }

    public function eraseCredentials()
    {
    }

    public function follow(User $user)
    {
        if ($this->getFollowing()->contains($user) === false) {
            $this->getFollowing()->add($user);
        }
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     *
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     *
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowing()
    {
        return $this->following;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * String representation of object
     *
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return \serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param mixed $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword = null): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     *
     *
     * @param mixed $roles
     *
     * @return self
     */
    public function setRoles(array $roles = null): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Constructs the object
     *
     * @link https://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->password
        ] = \unserialize($serialized);
    }
}
