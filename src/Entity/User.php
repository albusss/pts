<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    /**
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     *
     * @var string|null
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string|null
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    private $following;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="50", min="4")
     *
     * @var string|null
     */
    private $fullName;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096", min="8")
     *
     * @var string|null
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    private $postsLiked;

    /**
     * @ORM\Column(type="simple_array")
     *
     * @var string[]|null
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max="50", min="5")
     *
     * @var string|null
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
        $this->enabled = false;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return \App\Entity\User
     */
    public function follow(User $user): self
    {
        if ($this->getFollowing()->contains($user) === false) {
            $this->getFollowing()->add($user);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return bool|null
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostsLiked(): Collection
    {
        return $this->postsLiked;
    }

    /**
     * @return string[]|null
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getUsername()
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
            $this->password,
            $this->enabled
        ]);
    }

    /**
     * @param string|null $confirmationToken
     *
     * @return \App\Entity\User
     */
    public function setConfirmationToken(?string $confirmationToken = null): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @param string|null $email
     *
     * @return \App\Entity\User
     */
    public function setEmail(?string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param bool|null $enabled
     *
     * @return \App\Entity\User
     */
    public function setEnabled(?bool $enabled = null): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @param string|null $fullName
     *
     * @return \App\Entity\User
     */
    public function setFullName(?string $fullName = null): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @param string|null $password
     *
     * @return \App\Entity\User
     */
    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return self
     */
    public function setPlainPassword(?string $plainPassword = null): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @param string[]|null $roles
     *
     * @return \App\Entity\User
     */
    public function setRoles(?array $roles = null): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string|null $username
     *
     * @return \App\Entity\User
     */
    public function setUsername(?string $username = null): self
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
            $this->password,
            $this->enabled
        ] = \unserialize($serialized);
    }
}
